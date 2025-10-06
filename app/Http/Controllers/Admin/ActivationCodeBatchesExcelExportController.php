<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivationCodeBatch;
use App\Exports\ActivationCodeBatchExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ActivationCodeBatchesExcelExportController extends Controller
{
    // صفحة التحكم في القالب والمعاينة
    public function template(ActivationCodeBatch $batch, Request $request)
    {
        $template = $request->get('template');
        return view('admin.activation_codes.batches.excel_template', [
            'batch' => $batch,
            'codes' => $batch->activationCodes()->orderBy('id')->get(),
            'template' => $template,
        ]);
    }

    // تصدير الملف إلى Excel
    public function exportExcel(ActivationCodeBatch $batch, Request $request)
    {
        $template = $request->get('template');
        $export = new ActivationCodeBatchExport($batch, $template);
        $filename = 'batch_' . $batch->id . '_codes.xlsx';
        return Excel::download($export, $filename);
    }
}
