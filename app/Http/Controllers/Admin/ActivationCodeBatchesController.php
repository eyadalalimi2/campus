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
            ->withCount('activationCodes')
            ->orderByDesc('id');

        // فلاتر اختيارية
        if ($r->filled('status'))       $q->where('status', $r->status);
        if ($r->filled('plan_id'))      $q->where('plan_id', (int)$r->plan_id);
        if ($r->filled('university_id'))$q->where('university_id', (int)$r->university_id);
        if ($s = $r->get('q'))          $q->where('name', 'like', "%{$s}%");

        $batches = $q->paginate(15)->withQueryString();

        $plans        = Plan::orderBy('name')->get();
        $universities = University::orderBy('name')->get();

        return view('admin.activation_codes.batches.index', compact('batches','plans','universities'));
    }

    public function create()
    {
        $plans        = Plan::orderBy('name')->get();
        $universities = University::orderBy('name')->get();
        $colleges     = College::with('university')->orderBy('name')->get();
        $majors       = Major::with('college')->orderBy('name')->get();

        return view('admin.activation_codes.batches.create', compact('plans','universities','colleges','majors'));
    }

    public function store(StoreBatchRequest $request, ActivationCodeGenerator $generator)
    {
        $data = $request->validated();
        $data['created_by_admin_id'] = auth('admin')->id();

        DB::transaction(function () use (&$batch, $data, $request, $generator) {
            $batch = ActivationCodeBatch::create($data);

            // توليد الأكواد فوراً (اختياري حسب checkbox اسمه generate_now)
            if ($request->boolean('generate_now')) {
                $count   = (int) ($batch->quantity ?: 0);
                if ($count > 0) {
                    $codes = $generator->generateUniqueSet(
                        total: $count,
                        length: (int)$batch->code_length,
                        prefix: (string)($batch->code_prefix ?? '')
                    );

                    $rows = [];
                    $now  = now();

                    foreach ($codes as $code) {
                        $rows[] = [
                            'batch_id'           => $batch->id,
                            'code'               => $code,
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
                            'created_by_admin_id'=> $batch->created_by_admin_id,
                            'created_at'         => $now,
                            'updated_at'         => $now,
                        ];
                    }

                    if (!empty($rows)) {
                        ActivationCode::insert($rows);
                    }
                }
            }
        });

        // مهم: مرر $batch للـroute كي لا يظهر خطأ Missing parameter
        return redirect()
            ->route('admin.activation_code_batches.edit', $batch)
            ->with('success', 'تم إنشاء الدفعة بنجاح.');
    }

    public function show(ActivationCodeBatch $activation_code_batch)
    {
        $batch = $activation_code_batch->loadCount('activationCodes');
        $codes = ActivationCode::where('batch_id', $batch->id)
                    ->orderBy('id')->paginate(25);

        return view('admin.activation_codes.batches.show', compact('batch','codes'));
    }

    public function edit(ActivationCodeBatch $activation_code_batch)
    {
        $batch       = $activation_code_batch;
        $plans        = Plan::orderBy('name')->get();
        $universities = University::orderBy('name')->get();
        $colleges     = College::with('university')->orderBy('name')->get();
        $majors       = Major::with('college')->orderBy('name')->get();

        return view('admin.activation_codes.batches.edit', compact('batch','plans','universities','colleges','majors'));
    }

    public function update(StoreBatchRequest $request, ActivationCodeBatch $activation_code_batch)
    {
        $batch = $activation_code_batch;
        $data  = $request->validated();
        $batch->update($data);

        return redirect()
            ->route('admin.activation_code_batches.edit', $batch)
            ->with('success', 'تم تحديث الدفعة بنجاح.');
    }

    public function destroy(ActivationCodeBatch $activation_code_batch)
    {
        // FK على activation_codes(batch_id) = SET NULL، فالحذف آمن
        $activation_code_batch->delete();

        return redirect()
            ->route('admin.activation_code_batches.index')
            ->with('success', 'تم حذف الدفعة.');
    }

    public function export(ActivationCodeBatch $activation_code_batch): StreamedResponse
    {
        $batch = $activation_code_batch;

        $filename = 'batch_'.$batch->id.'_codes.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ];

        return response()->stream(function () use ($batch) {
            $out = fopen('php://output', 'w');

            // BOM للـExcel
            fwrite($out, chr(0xEF).chr(0xBB).chr(0xBF));

            // العناوين
            fputcsv($out, [
                'code','status','university_id','college_id','major_id',
                'start_policy','starts_on','valid_from','valid_until',
                'duration_days','max_redemptions','redemptions_count'
            ]);

            ActivationCode::where('batch_id', $batch->id)
                ->orderBy('id')
                ->chunk(1000, function ($chunk) use ($out) {
                    foreach ($chunk as $c) {
                        fputcsv($out, [
                            $c->code,
                            $c->status,
                            $c->university_id,
                            $c->college_id,
                            $c->major_id,
                            $c->start_policy,
                            optional($c->starts_on)->format('Y-m-d'),
                            optional($c->valid_from)->format('Y-m-d H:i:s'),
                            optional($c->valid_until)->format('Y-m-d H:i:s'),
                            $c->duration_days,
                            $c->max_redemptions,
                            $c->redemptions_count,
                        ]);
                    }
                });

            fclose($out);
        }, 200, $headers);
    }
}
