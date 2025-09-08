<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreActivationCodeRequest;
use App\Http\Requests\Admin\UpdateActivationCodeRequest;
use App\Models\ActivationCode;
use App\Services\CodeRedeemer;
use Illuminate\Http\Request;

class ActivationCodesController extends Controller
{
    public function index(Request $request)
    {
        $q = ActivationCode::query();

        if ($s = $request->get('s')) {
            $q->where('code','like',"%$s%");
        }
        if ($status = $request->get('status')) {
            $q->where('status',$status);
        }
        if ($batchId = $request->get('batch_id')) {
            $q->where('batch_id',$batchId);
        }

        $codes = $q->orderByDesc('id')->paginate(25);

        return view('admin.activation_codes.index', compact('codes'));
    }

    public function create()
    {
        return view('admin.activation_codes.create');
    }

    public function store(StoreActivationCodeRequest $request)
    {
        $data = $request->validated();

        $code = ActivationCode::create($data);

        return redirect()->route('admin.activation-codes.edit', $code->id)
            ->with('success','تم إنشاء الكود.');
    }

    public function edit(ActivationCode $activation_code)
    {
        $code = $activation_code;
        return view('admin.activation_codes.edit', compact('code'));
    }

    public function update(UpdateActivationCodeRequest $request, ActivationCode $activation_code)
    {
        $data = $request->validated();
        $activation_code->update($data);

        return redirect()->route('admin.activation-codes.edit', $activation_code->id)
            ->with('success','تم تحديث الكود.');
    }

    public function destroy(ActivationCode $activation_code)
    {
        if ($activation_code->status === 'redeemed') {
            return back()->withErrors(['error'=>'لا يمكن حذف كود تم استرداده.']);
        }
        $activation_code->delete();
        return redirect()->route('admin.activation-codes.index')->with('success','تم حذف الكود.');
    }

    public function redeemForm()
    {
        return view('admin.activation_codes.redeem');
    }

    public function redeemProcess(Request $request, CodeRedeemer $redeemer)
    {
        $data = $request->validate([
            'code'    => ['required','string'],
            'user_id' => ['required','integer','exists:users,id'],
        ]);

        try {
            $redeemer->redeem($data['code'], (int)$data['user_id']);
            return back()->with('success','تم استرداد الكود بنجاح.');
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
