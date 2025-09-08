<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBatchRequest;
use App\Models\ActivationCodeBatch;
use App\Models\ActivationCode;
use App\Models\Plan;
use App\Models\University;
use App\Models\College;
use App\Models\Major;
use App\Services\ActivationCodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ActivationCodeBatchesController extends Controller
{
    public function index(Request $r)
    {
        $q = ActivationCodeBatch::query()
            ->with('plan')
            ->withCount(['activationCodes', 'activationCodes as redeemed_count' => function ($w) {
                $w->where('redemptions_count', '>', 0);
            }])
            ->latest('id');

        if ($s = trim((string)$r->get('q'))) {
            $q->where('name','like',"%{$s}%");
        }

        $batches = $q->paginate(15)->withQueryString();
        return view('admin.activation_codes.batches.index', compact('batches'));
    }

    public function create()
    {
        $plans        = Plan::orderBy('name')->get();
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();
        return view('admin.activation_codes.batches.create', compact('plans','universities','colleges','majors'));
    }

    public function store(StoreBatchRequest $request)
    {
        $data = $request->validated();

        // إنشاء الدفعة
        $batch = ActivationCodeBatch::create([
            'name'               => $data['name'],
            'notes'              => $data['notes'] ?? '',
            'plan_id'            => $data['plan_id'],
            'university_id'      => $data['university_id'] ?? null,
            'college_id'         => $data['college_id'] ?? null,
            'major_id'           => $data['major_id'] ?? null,
            'quantity'           => $data['quantity'],
            'status'             => $data['status'] ?? 'active', // نفعلها مباشرة
            'duration_days'      => $data['duration_days'],
            'start_policy'       => $data['start_policy'],
            'starts_on'          => $data['starts_on'] ?? null,
            'valid_from'         => $data['valid_from'] ?? null,
            'valid_until'        => $data['valid_until'] ?? null,
            'code_prefix'        => $data['code_prefix'] ?? null,
            'code_length'        => $data['code_length'] ?? 10,
            'created_by_admin_id'=> auth('admin')->id(),
        ]);

        // توليد الأكواد الفريدة
        $length = max(4, min(24, (int)$batch->code_length ?: 10));
        $codes  = ActivationCodeGenerator::generateUniqueSet(
            (int)$batch->quantity,
            $length,
            $batch->code_prefix
        );

        // بناء صفوف الإدراج
        $rows = ActivationCodeGenerator::buildInsertRows($codes, [
            'batch_id'           => $batch->id,
            'plan_id'            => $batch->plan_id,
            'university_id'      => $batch->university_id,
            'college_id'         => $batch->college_id,
            'major_id'           => $batch->major_id,
            'duration_days'      => $batch->duration_days,
            'start_policy'       => $batch->start_policy,
            'starts_on'          => $batch->starts_on,
            'valid_from'         => $batch->valid_from,
            'valid_until'        => $batch->valid_until,
            'max_redemptions'    => 1,
            'redemptions_count'  => 0,
            'status'             => 'active',
            'created_by_admin_id'=> auth('admin')->id(),
        ]);

        DB::table('activation_codes')->insert($rows);

        return redirect()
            ->route('admin.activation_code_batches.show', $batch)
            ->with('success','تم إنشاء الدفعة وتوليد الأكواد بنجاح.');
    }

    public function show(ActivationCodeBatch $batch, Request $r)
    {
        $codes = $batch->activationCodes()
            ->when($r->filled('status'), fn($q)=>$q->where('status',$r->status))
            ->when($r->filled('q'), fn($q)=>$q->where('code','like','%'.$r->q.'%'))
            ->latest('id')->paginate(25)->withQueryString();

        return view('admin.activation_codes.batches.show', compact('batch','codes'));
    }

    public function edit(ActivationCodeBatch $batch)
    {
        $plans        = Plan::orderBy('name')->get();
        $universities = University::orderBy('name')->get();
        $colleges     = College::orderBy('name')->get();
        $majors       = Major::orderBy('name')->get();

        return view('admin.activation_codes.batches.edit', compact('batch','plans','universities','colleges','majors'));
    }

    public function update(StoreBatchRequest $request, ActivationCodeBatch $batch)
    {
        $data = $request->validated();

        $batch->update([
            'name'          => $data['name'],
            'notes'         => $data['notes'] ?? '',
            'plan_id'       => $data['plan_id'],
            'university_id' => $data['university_id'] ?? null,
            'college_id'    => $data['college_id'] ?? null,
            'major_id'      => $data['major_id'] ?? null,
            'status'        => $data['status'] ?? $batch->status,
            'duration_days' => $data['duration_days'],
            'start_policy'  => $data['start_policy'],
            'starts_on'     => $data['starts_on'] ?? null,
            'valid_from'    => $data['valid_from'] ?? null,
            'valid_until'   => $data['valid_until'] ?? null,
            'code_prefix'   => $data['code_prefix'] ?? null,
            'code_length'   => $data['code_length'] ?? $batch->code_length,
        ]);

        return redirect()->route('admin.activation_code_batches.show',$batch)->with('success','تم تحديث الدفعة.');
    }

    public function destroy(ActivationCodeBatch $batch)
    {
        $batch->delete();
        return redirect()->route('admin.activation_code_batches.index')->with('success','تم حذف الدفعة.');
    }

    public function export(ActivationCodeBatch $batch): StreamedResponse
    {
        $filename = 'batch_'.$batch->id.'_codes.csv';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($batch) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, [
                'ID','CODE','PLAN_ID','UNIVERSITY_ID','COLLEGE_ID','MAJOR_ID',
                'DURATION_DAYS','START_POLICY','STARTS_ON','VALID_FROM','VALID_UNTIL',
                'MAX_REDEMPTIONS','REDEMPTIONS_COUNT','STATUS','REDEEMED_BY','REDEEMED_AT','CREATED_AT'
            ]);

            $batch->loadMissing('activationCodes');
            foreach ($batch->activationCodes as $code) {
                fputcsv($out, [
                    $code->id,
                    $code->code,
                    $code->plan_id,
                    $code->university_id,
                    $code->college_id,
                    $code->major_id,
                    $code->duration_days,
                    $code->start_policy,
                    optional($code->starts_on)->format('Y-m-d'),
                    optional($code->valid_from)->format('Y-m-d H:i:s'),
                    optional($code->valid_until)->format('Y-m-d H:i:s'),
                    $code->max_redemptions,
                    $code->redemptions_count,
                    $code->status,
                    $code->redeemed_by_user_id,
                    optional($code->redeemed_at)->format('Y-m-d H:i:s'),
                    optional($code->created_at)->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }

    public function disable(ActivationCodeBatch $batch)
    {
        // إيقاف الدفعة وأكوادها
        $batch->update(['status'=>'disabled']);
        ActivationCode::where('batch_id',$batch->id)->update(['status'=>'disabled']);
        return back()->with('success','تم إيقاف الدفعة وجميع الأكواد.');
    }
}
