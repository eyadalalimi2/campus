<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreActivationCodeRequest;
use App\Http\Requests\Admin\UpdateActivationCodeRequest;
use App\Models\ActivationCode;
use App\Models\ActivationCodeBatch;
use App\Models\Plan;
use App\Models\University;
use App\Models\College;
use App\Models\Major;
use App\Models\User;
use App\Services\ActivationCodeGenerator;
use App\Services\CodeRedeemer;
use Illuminate\Http\Request;

class ActivationCodesController extends Controller
{
    public function index(Request $r)
    {
        $q = ActivationCode::with(['batch','plan','university','college','major','redeemedBy'])
            ->latest('id');

        if ($s = trim((string)$r->get('q')))     $q->where('code','like',"%{$s}%");
        if ($r->filled('status'))               $q->where('status',$r->status);
        if ($r->filled('batch_id'))             $q->where('batch_id',(int)$r->batch_id);
        if ($r->filled('university_id'))        $q->where('university_id',(int)$r->university_id);
        if ($r->filled('college_id'))           $q->where('college_id',(int)$r->college_id);
        if ($r->filled('major_id'))             $q->where('major_id',(int)$r->major_id);

        if ($r->filled('created_from')) $q->whereDate('created_at','>=',$r->created_from);
        if ($r->filled('created_to'))   $q->whereDate('created_at','<=',$r->created_to);

        $codes = $q->paginate(25)->withQueryString();

        $batches     = ActivationCodeBatch::orderBy('id','desc')->get(['id','name']);
        $plans       = Plan::orderBy('name')->get();
        $universities= University::orderBy('name')->get();
        $colleges    = College::orderBy('name')->get();
        $majors      = Major::orderBy('name')->get();

        return view('admin.activation_codes.index', compact('codes','batches','plans','universities','colleges','majors'));
    }

    public function create()
    {
        $batches     = ActivationCodeBatch::orderBy('id','desc')->get(['id','name']);
        $plans       = Plan::orderBy('name')->get();
        $universities= University::orderBy('name')->get();
        $colleges    = College::orderBy('name')->get();
        $majors      = Major::orderBy('name')->get();
        return view('admin.activation_codes.create', compact('batches','plans','universities','colleges','majors'));
    }

    public function store(StoreActivationCodeRequest $request)
    {
        $data = $request->validated();

        // إذا لم تُرسل code ننشئ واحدًا بطول 10 (أو استنادًا لطول دفعة مرتبطة)
        if (empty($data['code'])) {
            $length = 10;
            $prefix = null;
            if (!empty($data['batch_id'])) {
                $batch = ActivationCodeBatch::find($data['batch_id']);
                if ($batch) {
                    $length = (int)($batch->code_length ?: 10);
                    $prefix = $batch->code_prefix;
                }
            }
            $generated = ActivationCodeGenerator::generateUniqueSet(1, $length, $prefix);
            $data['code'] = $generated[0];
        }

        $data['status'] = $data['status'] ?? 'active';
        $data['created_by_admin_id'] = auth('admin')->id();

        ActivationCode::create($data);

        return redirect()->route('admin.activation_codes.index')->with('success','تم إنشاء الكود.');
    }

    public function edit(ActivationCode $activation_code)
    {
        $code        = $activation_code;
        $batches     = ActivationCodeBatch::orderBy('id','desc')->get(['id','name']);
        $plans       = Plan::orderBy('name')->get();
        $universities= University::orderBy('name')->get();
        $colleges    = College::orderBy('name')->get();
        $majors      = Major::orderBy('name')->get();

        return view('admin.activation_codes.edit', compact('code','batches','plans','universities','colleges','majors'));
    }

    public function update(UpdateActivationCodeRequest $request, ActivationCode $activation_code)
    {
        $activation_code->update($request->validated());
        return redirect()->route('admin.activation_codes.index')->with('success','تم تحديث الكود.');
    }

    public function destroy(ActivationCode $activation_code)
    {
        $activation_code->delete();
        return back()->with('success','تم حذف الكود.');
    }

    // شاشة تفعيل يدوي
    public function redeemForm()
    {
        $users  = User::orderBy('name')->get(['id','name']);
        return view('admin.activation_codes.redeem', compact('users'));
    }

    public function redeem(Request $r, CodeRedeemer $redeemer)
    {
        $r->validate([
            'code'    => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        [$ok, $msg] = $redeemer->redeemByAdmin($r->string('code')->toString(), (int)$r->user_id, auth('admin')->id());

        return back()->with($ok ? 'success' : 'error', $msg);
    }

    public function disable(ActivationCode $code)
    {
        $code->update(['status'=>'disabled']);
        return back()->with('success','تم تعطيل الكود.');
    }
}
