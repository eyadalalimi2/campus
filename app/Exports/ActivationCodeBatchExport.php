<?php

namespace App\Exports;

use App\Models\ActivationCodeBatch;
use App\Models\ActivationCode;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class ActivationCodeBatchExport implements FromView, WithTitle
{
    protected $batch;
    protected $template;

    public function __construct(ActivationCodeBatch $batch, $template = null)
    {
        $this->batch = $batch;
        $this->template = $template;
    }

    public function view(): View
    {
        $codes = ActivationCode::where('batch_id', $this->batch->id)->orderBy('id')->get();
        // إذا كان التصدير إلى Excel استخدم قالب خاص (جدول فقط)
        if (request()->routeIs('admin.activation_code_batches.export_excel')) {
            return view('admin.activation_codes.batches.excel_export', [
                'batch' => $this->batch,
                'codes' => $codes,
            ]);
        }
        // للمعاينة استخدم القالب الكامل
        return view('admin.activation_codes.batches.excel_template', [
            'batch' => $this->batch,
            'codes' => $codes,
            'template' => $this->template,
        ]);
    }

    public function title(): string
    {
        return 'دفعة الأكواد';
    }
}
