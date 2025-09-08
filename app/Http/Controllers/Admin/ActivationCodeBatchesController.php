<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBatchRequest;
use App\Models\ActivationCodeBatch;
use App\Services\ActivationCodeGenerator;
use Illuminate\Http\Request;

class ActivationCodeBatchesController extends Controller
{
    public function index(Request $request)
    {
        $q = ActivationCodeBatch::query();

        if ($s = $request->get('s')) {
            $q->where(function($w) use ($s) {
                $w->where('name','like',"%$s%")
                  ->orWhere('notes','like',"%$s%");
            });
        }
        if ($status = $request->get('status')) {
            $q->where('status', $status);
        }

        $batches = $q->orderByDesc('id')->paginate(20);

        return view('admin.activation_codes.batches.index', compact('batches'));
    }

    public function create()
    {
        return view('admin.activation_codes.batches.create');
    }

    public function store(StoreBatchRequest $request)
    {
        $data = $request->validated();
        $batch = ActivationCodeBatch::create($data);

        return redirect()->route('admin.activation-code-batches.show', $batch->id)
            ->with('success', 'تم إنشاء الدفعة بنجاح.');
    }

    public function show(ActivationCodeBatch $activation_code_batch)
    {
        $batch = $activation_code_batch->loadCount('activationCodes');
        return view('admin.activation_codes.batches.show', compact('batch'));
    }

    public function edit(ActivationCodeBatch $activation_code_batch)
    {
        $batch = $activation_code_batch;
        return view('admin.activation_codes.batches.edit', compact('batch'));
    }

    public function update(StoreBatchRequest $request, ActivationCodeBatch $activation_code_batch)
    {
        $data = $request->validated();
        $activation_code_batch->update($data);

        return redirect()->route('admin.activation-code-batches.show', $activation_code_batch->id)
            ->with('success', 'تم تحديث الدفعة بنجاح.');
    }

    public function destroy(ActivationCodeBatch $activation_code_batch)
    {
        if (!$activation_code_batch->isDraft() && $activation_code_batch->activationCodes()->exists()) {
            return back()->withErrors(['error' => 'لا يمكن حذف الدفعة النشطة/التي تحتوي أكواد.']);
        }

        $activation_code_batch->delete();
        return redirect()->route('admin.activation-code-batches.index')->with('success','تم حذف الدفعة.');
    }

    public function generate(ActivationCodeBatch $activation_code_batch, ActivationCodeGenerator $generator)
    {
        if (!$activation_code_batch->isActive() && !$activation_code_batch->isDraft()) {
            return back()->withErrors(['error'=>'يجب أن تكون الدفعة Draft أو Active لتوليد الأكواد.']);
        }

        $codes = $generator->generateForBatch($activation_code_batch);
        return back()->with('success', 'تم توليد ' . count($codes) . ' كود بنجاح.');
    }

    public function activate(ActivationCodeBatch $activation_code_batch)
    {
        $activation_code_batch->update(['status' => 'active']);
        return back()->with('success','تم تفعيل الدفعة.');
    }

    public function disable(ActivationCodeBatch $activation_code_batch)
    {
        $activation_code_batch->update(['status' => 'disabled']);
        return back()->with('success','تم تعطيل الدفعة.');
    }

    public function archive(ActivationCodeBatch $activation_code_batch)
    {
        $activation_code_batch->update(['status' => 'archived']);
        return back()->with('success','تم أرشفة الدفعة.');
    }
}
