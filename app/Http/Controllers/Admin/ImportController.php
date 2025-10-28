<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Models\University;
use App\Models\UniversityBranch;
use App\Models\College;
use App\Models\Major;
use App\Models\MedDevice;
use App\Models\MedSubject;
use App\Models\MedTopic;
use App\Models\MedDoctor;

class ImportController extends Controller
{
    protected $types = [
        'universities',
        'branches',
        'colleges',
        'majors',
        'med_subjects',
        'med_devices',
        'med_topics',
        'med_doctors',
    ];

    // Human-friendly Arabic labels for each import type
    protected $typeLabels = [
        'universities' => 'الجامعات',
        'branches' => 'الفروع',
        'colleges' => 'الكليات',
        'majors' => 'التخصصات',
        'med_devices' => 'أجهزة طبية',
        'med_subjects' => 'مواد طبية',
        'med_topics' => 'مواضيع طبية',
        'med_doctors' => 'دكاترة',
    ];

    public function index()
    {
        return view('admin.imports.index', ['types' => $this->types]);
    }

    public function show($type)
    {
        if (!in_array($type, $this->types)) abort(404);
        // If there is a stored preview but it wasn't just created (no flash flag),
        // remove it so previews do not persist across manual page refreshes.
        if (session()->has('import_preview') && ! session()->has('import_preview_shown')) {
            session()->forget('import_preview');
        }

        return view('admin.imports.upload', ['type' => $type]);
    }

    /**
     * Return a simple CSV template for the given type.
     */
    public function template($type)
    {
        if (!in_array($type, $this->types)) abort(404);

        $templates = [
            // add 'logo' header to universities template so admins can provide logo path during import
            'universities' => ['name', 'address', 'phone', 'is_active', 'logo'],
            'branches'     => ['university_id', 'name', 'address', 'phone', 'is_active', 'email'],
            'colleges'     => ['university_id', 'branch_id', 'name', 'is_active'],
            'majors'       => ['college_id', 'name', 'is_active'],
            // medical-related templates
            'med_devices'  => ['name', 'status', 'order_index', 'image_path', 'subject_ids'],
            'med_subjects' => ['name', 'scope', 'status', 'order_index', 'image_path'],
            'med_topics'   => ['subject_id', 'name', 'order_index', 'status'],
            'med_doctors'  => ['name', 'status', 'order_index', 'image_path', 'subject_ids'],
        ];

        $headers = $templates[$type];

        $filename = $type . '-template.xlsx';

        // use TemplateExport to generate an XLSX with only headings
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\TemplateExport($headers), $filename);
    }

    public function upload(Request $request, $type)
    {
        if (!in_array($type, $this->types)) abort(404);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $path = $request->file('file')->store('imports');

        // Try to read via maatwebsite/excel into array
        try {
            $sheets = Excel::toArray(null, storage_path('app/' . $path));
            $rows = $sheets[0] ?? [];
        } catch (\Throwable $e) {
            return back()->withErrors(['file' => 'فشل قراءة الملف: ' . $e->getMessage()]);
        }

        // assume first row headers
        if (count($rows) === 0) {
            return back()->withErrors(['file' => 'الملف فارغ']);
        }

        $headers = array_map(function ($h) {
            return strtolower(trim((string)$h));
        }, $rows[0]);
        $dataRows = array_slice($rows, 1);

        // required header per type
        $requiredHeaders = [
            'universities' => ['name'],
            'branches'     => ['university_id', 'name'],
            'colleges'     => ['university_id', 'branch_id', 'name'],
            'majors'       => ['college_id', 'name'],
        ];

        $missing = array_diff($requiredHeaders[$type], $headers);
        if (!empty($missing)) {
            return back()->withErrors(['file' => 'أعمدة مفقودة: ' . implode(', ', $missing)]);
        }

        // Delegate processing to centralized processor (handles preview/confirm and pivot attaching)
        $report = $this->processFile($path, $type, true);

        session()->flash('success', "نتيجة الاستيراد: تم إنشاء {$report['created']} — تم تحديث " . ($report['updated'] ?? 0) . " — تم تخطي {$report['skipped']} — فشل {$report['failed']}");
        session()->flash('import_report', $report);

        return redirect()->route('admin.imports.index');
    }

    /**
     * Preview uploaded file: store file and prepare a dry-run report
     */
    public function preview(Request $request, $type)
    {
        if (!in_array($type, $this->types)) abort(404);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        $path = $request->file('file')->store('imports');

        // process in dry-run mode (no DB writes)
        $report = $this->processFile($path, $type, false);

        // save preview (path + report) so confirm can use it
    session()->put('import_preview', ['path' => $path, 'report' => $report]);
    // mark that preview was just created so show() will display it once; a manual
    // page refresh (which does not contain this flash) will cause the preview to be cleared.
    session()->flash('import_preview_shown', true);

        return redirect()->route('admin.imports.show', $type)->with('success', 'تم تحليل الملف — راجع المعاينة قبل التأكيد.');
    }

    /**
     * Confirm and persist previously previewed file
     */
    public function confirm(Request $request, $type)
    {
        if (!in_array($type, $this->types)) abort(404);

        $preview = session()->get('import_preview');
        if (!$preview || !isset($preview['path'])) {
            return back()->withErrors(['file' => 'لا يوجد معاينة صالحة. ارفع الملف أولاً ثم اضغط معاينة.']);
        }

        $path = $preview['path'];

        $report = $this->processFile($path, $type, true);

        // remove preview and store final report
        session()->forget('import_preview');
    session()->flash('import_report', $report);
    session()->flash('success', "تم استيراد: {$report['created']} عناصر. تم تحديث: " . ($report['updated'] ?? 0) . ". فشل: {$report['failed']}. تم تخطي: {$report['skipped']}.");

        return redirect()->route('admin.imports.index');
    }

    /**
     * Export errors (from preview or last import) as CSV
     */
    public function errorsExport($type)
    {
        if (!in_array($type, $this->types)) abort(404);

        $report = session('import_preview.report') ?? session('import_report');
        if (!$report || empty($report['errors'])) {
            return back()->withErrors(['file' => 'لا توجد أخطاء للتصدير.']);
        }

        $filename = $type . '-import-errors-' . date('Ymd_His') . '.csv';

        $callback = function () use ($report) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['row', 'messages', 'raw_json']);
            foreach ($report['errors'] as $e) {
                $row = $e['row'] ?? '';
                $messages = is_array($e['messages']) ? implode(' | ', $e['messages']) : (string)($e['messages'] ?? '');
                $raw = json_encode($e['raw'] ?? [], JSON_UNESCAPED_UNICODE);
                fputcsv($out, [$row, $messages, $raw]);
            }
            fclose($out);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Build a Validator with Arabic messages and attribute names.
     */
    private function makeValidator(string $type, array $data, array $rules)
    {
        $messages = [
            'required' => 'حقل :attribute مطلوب.',
            'integer'  => 'حقل :attribute يجب أن يكون رقمًا صحيحًا.',
            'email'    => 'حقل :attribute يجب أن يكون بريداً إلكترونياً صحيحاً.',
            'max'      => 'حقل :attribute طويل جداً (الحد الأقصى :max).',
            'string'   => 'حقل :attribute يجب أن يكون نصاً.',
        ];

        $attributes = [
            'name' => 'الاسم',
            'address' => 'العنوان',
            'phone' => 'الهاتف',
            'is_active' => 'حالة التفعيل',
            'university_id' => 'معرّف الجامعة',
            'subject_id' => 'معرّف المادة',
            'branch_id' => 'معرّف الفرع',
            'college_id' => 'معرّف الكلية',
            'email' => 'البريد الإلكتروني',
            // medical import fields
            'subject_id_or_name' => 'معرّف/اسم المادة',
            'device_ids' => 'معرّفات الأجهزة',
            'device_subject_ids' => 'معرّفات مواد الجهاز',
            'subject_ids' => 'معرّفات المواد',
            'order_index' => 'ترتيب العرض',
            'image_path' => 'مسار الصورة',
            'logo' => 'الشعار',
            'logo_path' => 'مسار الشعار',
        ];

        return Validator::make($data, $rules, $messages, $attributes);
    }

    /**
     * Core file processing (parse, validate, optionally persist). Returns report.
     */
    protected function processFile(string $path, string $type, bool $persist = false)
    {
        // Try to read via maatwebsite/excel into array
        try {
            $sheets = Excel::toArray(null, storage_path('app/' . $path));
            $rows = $sheets[0] ?? [];
        } catch (\Throwable $e) {
            return ['error' => 'فشل قراءة الملف: ' . $e->getMessage()];
        }

        if (count($rows) === 0) {
            return ['error' => 'الملف فارغ'];
        }

        $headers = array_map(function ($h) {
            return strtolower(trim((string)$h));
        }, $rows[0]);
        $dataRows = array_slice($rows, 1);

        // required header per type. We accept either *_id or *_id_or_name forms
        $requiredHeaders = [
            'universities' => ['name'],
            // branches: accept 'university_id' OR 'university_id_or_name'
            'branches'     => ['university_id_or_name', 'name'],
            'colleges'     => ['branch_id_or_name', 'name'],
            'majors'       => ['college_id_or_name', 'name'],
        ];

        // validate presence of required headers, allowing the common alternative names
        $missing = [];
        if ($type === 'universities') {
            if (!in_array('name', $headers)) $missing[] = 'name';
        } elseif ($type === 'branches') {
            if (!in_array('university_id', $headers) && !in_array('university_id_or_name', $headers)) $missing[] = 'university_id|university_id_or_name';
            if (!in_array('name', $headers)) $missing[] = 'name';
        } elseif ($type === 'colleges') {
            if (!in_array('branch_id', $headers) && !in_array('branch_id_or_name', $headers)) $missing[] = 'branch_id|branch_id_or_name';
            if (!in_array('name', $headers)) $missing[] = 'name';
        } elseif ($type === 'majors') {
            if (!in_array('college_id', $headers) && !in_array('college_id_or_name', $headers)) $missing[] = 'college_id|college_id_or_name';
            if (!in_array('name', $headers)) $missing[] = 'name';
        }

        if (!empty($missing)) {
            return ['error' => 'أعمدة مفقودة: ' . implode(', ', $missing)];
        }

    $created = 0;
    $skipped = 0;
    $failed = 0;
    $updated = 0;
    $errors = [];
    $rowsList = [];

        foreach ($dataRows as $index => $row) {
            $rowNumber = $index + 2;
            $rowAssoc = [];
            foreach ($headers as $i => $h) {
                $rowAssoc[$h] = isset($row[$i]) ? (is_string($row[$i]) ? trim($row[$i]) : $row[$i]) : null;
            }

            // normalize phone to string so numeric-only phones don't fail 'string' validation
            if (isset($rowAssoc['phone']) && !is_string($rowAssoc['phone'])) {
                $rowAssoc['phone'] = (string) $rowAssoc['phone'];
            }

            // normalize alternative id_or_name keys: accept headers like 'university_id', 'branch_id', 'college_id'
            // and map them to the _or_name keys expected by validation/processing
            if (!isset($rowAssoc['university_id_or_name']) && isset($rowAssoc['university_id'])) {
                $rowAssoc['university_id_or_name'] = $rowAssoc['university_id'];
            }
            if (!isset($rowAssoc['branch_id_or_name']) && isset($rowAssoc['branch_id'])) {
                $rowAssoc['branch_id_or_name'] = $rowAssoc['branch_id'];
            }
            if (!isset($rowAssoc['college_id_or_name']) && isset($rowAssoc['college_id'])) {
                $rowAssoc['college_id_or_name'] = $rowAssoc['college_id'];
            }

            // accept 'logo' as alternative to 'logo_path' in uploaded files
            if (!isset($rowAssoc['logo_path']) && isset($rowAssoc['logo'])) {
                $rowAssoc['logo_path'] = $rowAssoc['logo'];
            }

            // support common alternative header names
            if (!isset($rowAssoc['university_id_or_name'])) {
                if (isset($rowAssoc['university'])) $rowAssoc['university_id_or_name'] = $rowAssoc['university'];
                if (isset($rowAssoc['uni_id'])) $rowAssoc['university_id_or_name'] = $rowAssoc['uni_id'];
                if (isset($rowAssoc['uni'])) $rowAssoc['university_id_or_name'] = $rowAssoc['uni'];
            }
            if (!isset($rowAssoc['branch_id_or_name'])) {
                if (isset($rowAssoc['branch'])) $rowAssoc['branch_id_or_name'] = $rowAssoc['branch'];
                if (isset($rowAssoc['br_id'])) $rowAssoc['branch_id_or_name'] = $rowAssoc['br_id'];
            }
            if (!isset($rowAssoc['college_id_or_name'])) {
                if (isset($rowAssoc['college'])) $rowAssoc['college_id_or_name'] = $rowAssoc['college'];
                if (isset($rowAssoc['col_id'])) $rowAssoc['college_id_or_name'] = $rowAssoc['col_id'];
            }

            try {
                // Validation rules similar to upload()
                // collect the parsed row for preview output
                $rowsList[] = ['row' => $rowNumber, 'raw' => $rowAssoc];
                if ($type === 'universities') {
                    $v = $this->makeValidator($type, $rowAssoc, [
                        'name' => 'required|string|max:191',
                        'phone' => 'nullable|string|max:50',
                        'is_active' => 'nullable',
                        'logo' => 'nullable|string|max:255',
                        'logo_path' => 'nullable|string|max:255',
                    ]);
                    if ($v->fails()) {
                        $failed++;
                        $errors[] = ['row' => $rowNumber, 'messages' => $v->errors()->all(), 'raw' => $rowAssoc];
                        continue;
                    }

                    if (University::where('name', $rowAssoc['name'])->exists()) {
                        $skipped++;
                        $errors[] = ['row' => $rowNumber, 'messages' => ['موجود مسبقاً (تخطي)'], 'raw' => $rowAssoc];
                        continue;
                    }

                    if ($persist) {
                        // Some installations have `address` (or phone) set NOT NULL in DB.
                        // To avoid integrity errors when the Excel leaves these empty,
                        // persist empty string fallback instead of null.
                        University::create([
                            'name' => $rowAssoc['name'],
                            'address' => $rowAssoc['address'] ?? '',
                            'phone' => $rowAssoc['phone'] ?? '',
                            'logo_path' => $rowAssoc['logo_path'] ?? null,
                            'is_active' => in_array(strtolower((string)($rowAssoc['is_active'] ?? '1')), ['1', 'true', 'yes']),
                        ]);
                    }
                    $created++;
                }

                if ($type === 'branches') {
                    $v = $this->makeValidator($type, $rowAssoc, [
                        'university_id_or_name' => 'required',
                        'name' => 'required|string|max:191',
                    ]);
                    if ($v->fails()) {
                        $failed++;
                        $errors[] = ['row' => $rowNumber, 'messages' => $v->errors()->all(), 'raw' => $rowAssoc];
                        continue;
                    }

                    $u = $rowAssoc['university_id_or_name'];
                    $university = is_numeric($u) ? University::find((int)$u) : University::where('name', $u)->first();
                    if (!$university) {
                        $failed++;
                        $errors[] = ['row' => $rowNumber, 'messages' => ['الجامعة غير موجودة: ' . $u], 'raw' => $rowAssoc];
                        continue;
                    }

                    // For preview readability: if the uploaded header was 'university_id' or 'university_id_or_name'
                    // replace the raw value with the actual university name so the preview table shows the name.
                    if (isset($university) && $university) {
                        if (in_array('university_id', $headers)) {
                            $rowAssoc['university_id'] = $university->name;
                        }
                        if (in_array('university_id_or_name', $headers)) {
                            $rowAssoc['university_id_or_name'] = $university->name;
                        }

                        // update the preview rowsList entry so the displayed row shows the resolved name
                        if (!empty($rowsList)) {
                            $last = count($rowsList) - 1;
                            $rowsList[$last]['raw'] = $rowAssoc;
                        }
                    }

                    if (UniversityBranch::where('university_id', $university->id)->where('name', $rowAssoc['name'])->exists()) {
                        $skipped++;
                        $errors[] = ['row' => $rowNumber, 'messages' => ['الفرع موجود مسبقاً (تخطي)'], 'raw' => $rowAssoc];
                        continue;
                    }

                    if ($persist) {
                        UniversityBranch::create([
                            'university_id' => $university->id,
                            'name' => $rowAssoc['name'] ?? null,
                            'address' => $rowAssoc['address'] ?? null,
                            'phone' => $rowAssoc['phone'] ?? null,
                            'email' => $rowAssoc['email'] ?? null,
                            'is_active' => in_array(strtolower((string)($rowAssoc['is_active'] ?? '1')), ['1', 'true', 'yes']),
                        ]);
                    }
                    $created++;
                }

                if ($type === 'colleges') {
                    $v = $this->makeValidator($type, $rowAssoc, [
                        'branch_id_or_name' => 'required',
                        'name' => 'required|string|max:191',
                    ]);
                    if ($v->fails()) {
                        $failed++;
                        $errors[] = ['row' => $rowNumber, 'messages' => $v->errors()->all(), 'raw' => $rowAssoc];
                        continue;
                    }

                    $b = $rowAssoc['branch_id_or_name'];
                    $branch = is_numeric($b) ? UniversityBranch::find((int)$b) : UniversityBranch::where('name', $b)->first();
                    if (!$branch) {
                        $failed++;
                        $errors[] = ['row' => $rowNumber, 'messages' => ['الفرع غير موجود: ' . $b], 'raw' => $rowAssoc];
                        continue;
                    }

                        // Show branch name in preview if header used branch_id or branch_id_or_name
                        if (isset($branch) && $branch) {
                            if (in_array('branch_id', $headers)) {
                                $rowAssoc['branch_id'] = $branch->name;
                            }
                            if (in_array('branch_id_or_name', $headers)) {
                                $rowAssoc['branch_id_or_name'] = $branch->name;
                            }

                            if (!empty($rowsList)) {
                                $last = count($rowsList) - 1;
                                $rowsList[$last]['raw'] = $rowAssoc;
                            }
                        }

                    // Determine university relation if the uploaded file included a university column
                    $relationOk = null;
                    if (in_array('university_id', $headers) || in_array('university_id_or_name', $headers)) {
                        $uval = $rowAssoc['university_id'] ?? $rowAssoc['university_id_or_name'] ?? null;
                        if ($uval !== null) {
                            $uni = is_numeric($uval) ? University::find((int)$uval) : University::where('name', $uval)->first();
                            if ($uni) {
                                // replace raw university column with name for readability
                                if (in_array('university_id', $headers)) $rowAssoc['university_id'] = $uni->name;
                                if (in_array('university_id_or_name', $headers)) $rowAssoc['university_id_or_name'] = $uni->name;
                                // relation ok if branch belongs to this university
                                $relationOk = ($branch->university_id == $uni->id);
                            } else {
                                // university value provided but not found
                                $relationOk = false;
                            }
                        }
                    } else {
                        // no university column provided; we can still show if branch has a linked university
                        $relationOk = ($branch->university_id ? true : false);
                    }

                    // store relation flag in preview meta for this row
                    if (!empty($rowsList)) {
                        $last = count($rowsList) - 1;
                        $rowsList[$last]['meta'] = array_merge($rowsList[$last]['meta'] ?? [], ['relation_ok' => $relationOk]);
                        // update raw in case we modified university display
                        $rowsList[$last]['raw'] = $rowAssoc;
                    }

                    if (College::where('branch_id', $branch->id)->where('name', $rowAssoc['name'])->exists()) {
                        $skipped++;
                        $errors[] = ['row' => $rowNumber, 'messages' => ['الكلية موجودة مسبقاً (تخطي)'], 'raw' => $rowAssoc];
                        continue;
                    }

                    if ($persist) {
                        College::create([
                            // Ensure university_id is provided — the colleges table requires it
                            // Use the branch's university_id (authoritative) to avoid DB errors.
                            'university_id' => $branch->university_id,
                            'branch_id' => $branch->id,
                            'name' => $rowAssoc['name'] ?? null,
                            'is_active' => in_array(strtolower((string)($rowAssoc['is_active'] ?? '1')), ['1', 'true', 'yes']),
                        ]);
                    }
                    $created++;
                }

                if ($type === 'majors') {
                    $v = $this->makeValidator($type, $rowAssoc, [
                        'college_id_or_name' => 'required',
                        'name' => 'required|string|max:191',
                    ]);
                    if ($v->fails()) {
                        $failed++;
                        $errors[] = ['row' => $rowNumber, 'messages' => $v->errors()->all(), 'raw' => $rowAssoc];
                        continue;
                    }

                    $c = $rowAssoc['college_id_or_name'];
                    $college = is_numeric($c) ? College::find((int)$c) : College::where('name', $c)->first();
                    if (!$college) {
                        $failed++;
                        $errors[] = ['row' => $rowNumber, 'messages' => ['الكلية غير موجودة: ' . $c], 'raw' => $rowAssoc];
                        continue;
                    }

                        // Show college name in preview if header used college_id or college_id_or_name
                        if (isset($college) && $college) {
                            if (in_array('college_id', $headers)) {
                                $rowAssoc['college_id'] = $college->name;
                            }
                            if (in_array('college_id_or_name', $headers)) {
                                $rowAssoc['college_id_or_name'] = $college->name;
                            }

                            if (!empty($rowsList)) {
                                $last = count($rowsList) - 1;
                                $rowsList[$last]['raw'] = $rowAssoc;
                            }
                        }

                    if (Major::where('college_id', $college->id)->where('name', $rowAssoc['name'])->exists()) {
                        $skipped++;
                        $errors[] = ['row' => $rowNumber, 'messages' => ['التخصص موجود مسبقاً (تخطي)'], 'raw' => $rowAssoc];
                        continue;
                    }

                    if ($persist) {
                        Major::create([
                            'college_id' => $college->id,
                            'name' => $rowAssoc['name'] ?? null,
                            'is_active' => in_array(strtolower((string)($rowAssoc['is_active'] ?? '1')), ['1', 'true', 'yes']),
                        ]);
                    }
                    $created++;
                }
                // ---- medical imports ----
                if ($type === 'med_devices') {
                    $v = $this->makeValidator($type, $rowAssoc, [
                        'name' => 'required|string|max:191',
                        'status' => 'nullable',
                        // optional ordering, image path and related subjects
                        'order_index' => 'nullable|integer',
                        'image_path' => 'nullable|string|max:255',
                        'subject_ids' => 'nullable',
                    ]);
                    if ($v->fails()) {
                        $failed++;
                        $errors[] = ['row' => $rowNumber, 'messages' => $v->errors()->all(), 'raw' => $rowAssoc];
                        continue;
                    }
                    // Check if device already exists
                    $existing = MedDevice::where('name', $rowAssoc['name'])->first();

                    if ($persist) {
                        // Default status to 'published' when the uploaded value is empty or null
                        $status = isset($rowAssoc['status']) ? trim((string)$rowAssoc['status']) : '';
                        if ($status === '') {
                            $status = 'published';
                        }

                        if ($existing) {
                            // update device fields if provided
                            $existing->status = $status;
                            if (isset($rowAssoc['order_index']) && $rowAssoc['order_index'] !== '') {
                                $existing->order_index = (int)$rowAssoc['order_index'];
                            }
                            if (isset($rowAssoc['image_path']) && $rowAssoc['image_path'] !== '') {
                                $existing->image_path = $rowAssoc['image_path'];
                            }
                            $existing->save();

                            // attach subjects without detaching existing relations
                            if (!empty($rowAssoc['subject_ids'])) {
                                $tokens = preg_split('/[;,|]+/', (string)$rowAssoc['subject_ids']);
                                $attachIds = [];
                                $missing = [];
                                foreach ($tokens as $t) {
                                    $t = trim($t);
                                    if ($t === '') continue;
                                    if (is_numeric($t)) {
                                        $sub = MedSubject::find((int)$t);
                                    } else {
                                        $sub = MedSubject::where('name', $t)->first();
                                    }
                                    if ($sub) {
                                        $attachIds[] = $sub->id;
                                    } else {
                                        $missing[] = $t;
                                    }
                                }
                                if (!empty($attachIds)) {
                                    $existing->subjects()->syncWithoutDetaching($attachIds);
                                }
                                if (!empty($missing)) {
                                    $errors[] = ['row' => $rowNumber, 'messages' => ['بعض المواد المذكورة غير موجودة: ' . implode(', ', $missing)], 'raw' => $rowAssoc];
                                }
                            }

                            $updated++;
                        } else {
                            $device = MedDevice::create([
                                'name' => $rowAssoc['name'],
                                'status' => $status,
                                'order_index' => isset($rowAssoc['order_index']) && $rowAssoc['order_index'] !== '' ? (int)$rowAssoc['order_index'] : 0,
                                'image_path' => $rowAssoc['image_path'] ?? null,
                            ]);

                            // attach subjects (new device) replacing any defaults
                            if (!empty($rowAssoc['subject_ids'])) {
                                $tokens = preg_split('/[;,|]+/', (string)$rowAssoc['subject_ids']);
                                $attachIds = [];
                                $missing = [];
                                foreach ($tokens as $t) {
                                    $t = trim($t);
                                    if ($t === '') continue;
                                    if (is_numeric($t)) {
                                        $sub = MedSubject::find((int)$t);
                                    } else {
                                        $sub = MedSubject::where('name', $t)->first();
                                    }
                                    if ($sub) {
                                        $attachIds[] = $sub->id;
                                    } else {
                                        $missing[] = $t;
                                    }
                                }
                                if (!empty($attachIds)) {
                                    $device->subjects()->sync($attachIds);
                                }
                                if (!empty($missing)) {
                                    $errors[] = ['row' => $rowNumber, 'messages' => ['بعض المواد المذكورة غير موجودة: ' . implode(', ', $missing)], 'raw' => $rowAssoc];
                                }
                            }

                            $created++;
                        }
                    } else {
                        // preview mode: resolve subject_ids to names for display and warnings
                        if (!empty($rowAssoc['subject_ids'])) {
                            $tokens = preg_split('/[;,|]+/', (string)$rowAssoc['subject_ids']);
                            $resolvedNames = [];
                            $missing = [];
                            foreach ($tokens as $t) {
                                $t = trim($t);
                                if ($t === '') continue;
                                if (is_numeric($t)) {
                                    $sub = MedSubject::find((int)$t);
                                } else {
                                    $sub = MedSubject::where('name', $t)->first();
                                }
                                if ($sub) {
                                    $resolvedNames[] = $sub->name;
                                } else {
                                    $missing[] = $t;
                                }
                            }

                            $display = '';
                            if (!empty($resolvedNames)) $display = implode(', ', $resolvedNames);
                            if (!empty($missing)) {
                                $display = $display ? ($display . ' | مفقود: ' . implode(', ', $missing)) : ('مفقود: ' . implode(', ', $missing));
                            }

                            $rowAssoc['subject_ids'] = $display;
                            // update preview row display
                            if (!empty($rowsList)) {
                                $last = count($rowsList) - 1;
                                $rowsList[$last]['raw'] = $rowAssoc;
                                $rowsList[$last]['meta'] = array_merge($rowsList[$last]['meta'] ?? [], ['subject_resolution' => ['resolved' => $resolvedNames, 'missing' => $missing]]);
                            }

                            if (!empty($missing)) {
                                // non-fatal warning so admin can fix before confirming
                                $errors[] = ['row' => $rowNumber, 'messages' => ['بعض المواد المذكورة غير موجودة: ' . implode(', ', $missing)], 'raw' => $rowAssoc];
                            }
                        }

                        // preview mode: note existence
                        if ($existing) {
                            $skipped++;
                            $errors[] = ['row' => $rowNumber, 'messages' => ['الجهاز موجود مسبقاً (سيتم تحديثه عند التأكيد)'], 'raw' => $rowAssoc];
                        } else {
                            $created++;
                        }
                    }
                }

                if ($type === 'med_subjects') {
                    $v = $this->makeValidator($type, $rowAssoc, [
                        'name' => 'required|string|max:191',
                        'scope' => 'nullable|string|max:191',
                        'status' => 'nullable',
                        'order_index' => 'nullable|integer',
                        'image_path' => 'nullable|string|max:255',
                    ]);
                    if ($v->fails()) {
                        $failed++;
                        $errors[] = ['row' => $rowNumber, 'messages' => $v->errors()->all(), 'raw' => $rowAssoc];
                        continue;
                    }

                    if (MedSubject::where('name', $rowAssoc['name'])->exists()) {
                        $skipped++;
                        $errors[] = ['row' => $rowNumber, 'messages' => ['المادة موجودة مسبقاً (تخطي)'], 'raw' => $rowAssoc];
                        continue;
                    }

                    if ($persist) {
                        // Default status to 'published' when empty/null to avoid DB errors
                        $status = isset($rowAssoc['status']) ? trim((string)$rowAssoc['status']) : '';
                        if ($status === '') {
                            $status = 'published';
                        }

                        MedSubject::create([
                            'name' => $rowAssoc['name'],
                            'scope' => $rowAssoc['scope'] ?? null,
                            'status' => $status,
                            'order_index' => isset($rowAssoc['order_index']) && $rowAssoc['order_index'] !== '' ? (int)$rowAssoc['order_index'] : 0,
                            'image_path' => $rowAssoc['image_path'] ?? null,
                        ]);
                    }
                    $created++;
                }

                if ($type === 'med_topics') {
                    $v = $this->makeValidator($type, $rowAssoc, [
                        'subject_id' => 'required|integer',
                        'name' => 'required|string|max:191',
                        'status' => 'nullable',
                        'order_index' => 'nullable|integer',
                    ]);
                    if ($v->fails()) {
                        $failed++;
                        $errors[] = ['row' => $rowNumber, 'messages' => $v->errors()->all(), 'raw' => $rowAssoc];
                        continue;
                    }

                    $sref = $rowAssoc['subject_id'];
                    $subject = MedSubject::find((int)$sref);
                    if (!$subject) {
                        $failed++;
                        $errors[] = ['row' => $rowNumber, 'messages' => ['المادة غير موجودة بالمعرّف: ' . $sref], 'raw' => $rowAssoc];
                        continue;
                    }

                    // update preview display to show subject name instead of raw id
                    if (!empty($rowsList)) {
                        $last = count($rowsList) - 1;
                        if (in_array('subject_id', $headers)) $rowAssoc['subject_id'] = $subject->name;
                        $rowsList[$last]['raw'] = $rowAssoc;
                    }

                    if (MedTopic::where('subject_id', $subject->id)->where('title', $rowAssoc['name'])->exists()) {
                        $skipped++;
                        $errors[] = ['row' => $rowNumber, 'messages' => ['الموضوع موجود مسبقاً (تخطي)'], 'raw' => $rowAssoc];
                        continue;
                    }

                    if ($persist) {
                        // Default status to 'published' when empty/null
                        $status = isset($rowAssoc['status']) ? trim((string)$rowAssoc['status']) : '';
                        if ($status === '') {
                            $status = 'published';
                        }

                        MedTopic::create([
                            'subject_id' => $subject->id,
                            'title' => $rowAssoc['name'] ?? null,
                            'status' => $status,
                            'order_index' => isset($rowAssoc['order_index']) && $rowAssoc['order_index'] !== '' ? (int)$rowAssoc['order_index'] : 0,
                        ]);
                    }
                    $created++;
                }

                if ($type === 'med_doctors') {
                    // med_doctors now only accept name and status according to user request
                    $v = $this->makeValidator($type, $rowAssoc, [
                        'name' => 'required|string|max:191',
                        'status' => 'nullable',
                        'order_index' => 'nullable|integer',
                        'image_path' => 'nullable|string|max:255',
                    ]);
                    if ($v->fails()) {
                        $failed++;
                        $errors[] = ['row' => $rowNumber, 'messages' => $v->errors()->all(), 'raw' => $rowAssoc];
                        continue;
                    }

                    // In preview mode, resolve subject_ids to human-readable names for display
                    if (!$persist && !empty($rowAssoc['subject_ids'])) {
                        $tokens = preg_split('/[;,|]+/', (string)$rowAssoc['subject_ids']);
                        $resolvedNames = [];
                        $missing = [];
                        foreach ($tokens as $t) {
                            $t = trim($t);
                            if ($t === '') continue;
                            if (is_numeric($t)) {
                                $sub = MedSubject::find((int)$t);
                            } else {
                                $sub = MedSubject::where('name', $t)->first();
                            }
                            if ($sub) {
                                $resolvedNames[] = $sub->name;
                            } else {
                                $missing[] = $t;
                            }
                        }

                        $display = '';
                        if (!empty($resolvedNames)) $display = implode(', ', $resolvedNames);
                        if (!empty($missing)) {
                            $display = $display ? ($display . ' | مفقود: ' . implode(', ', $missing)) : ('مفقود: ' . implode(', ', $missing));
                        }

                        $rowAssoc['subject_ids'] = $display;
                        if (!empty($rowsList)) {
                            $last = count($rowsList) - 1;
                            $rowsList[$last]['raw'] = $rowAssoc;
                            $rowsList[$last]['meta'] = array_merge($rowsList[$last]['meta'] ?? [], ['subject_resolution' => ['resolved' => $resolvedNames, 'missing' => $missing]]);
                        }

                        if (!empty($missing)) {
                            $errors[] = ['row' => $rowNumber, 'messages' => ['بعض المواد المذكورة غير موجودة: ' . implode(', ', $missing)], 'raw' => $rowAssoc];
                        }
                    }

                    if (MedDoctor::where('name', $rowAssoc['name'])->exists()) {
                        $skipped++;
                        $errors[] = ['row' => $rowNumber, 'messages' => ['الدكتور موجود مسبقاً (تخطي)'], 'raw' => $rowAssoc];
                        continue;
                    }

                    if ($persist) {
                            // Default status to 'published' when empty/null
                            $status = isset($rowAssoc['status']) ? trim((string)$rowAssoc['status']) : '';
                            if ($status === '') {
                                $status = 'published';
                            }

                            // Create the doctor and optionally attach subjects
                            $doctor = MedDoctor::create([
                                'name' => $rowAssoc['name'],
                                'status' => $status,
                                'order_index' => isset($rowAssoc['order_index']) && $rowAssoc['order_index'] !== '' ? (int)$rowAssoc['order_index'] : 0,
                                'avatar_path' => $rowAssoc['image_path'] ?? null,
                            ]);

                            // If the import included a subject_ids column, parse and attach relations.
                            // Accept formats like "1,2,3" or "Anatomy;Physiology" where tokens may be IDs or names.
                            if (!empty($rowAssoc['subject_ids'])) {
                                $tokens = preg_split('/[;,|]+/', (string)$rowAssoc['subject_ids']);
                                $attachIds = [];
                                $missing = [];
                                foreach ($tokens as $t) {
                                    $t = trim($t);
                                    if ($t === '') continue;
                                    if (is_numeric($t)) {
                                        $sub = MedSubject::find((int)$t);
                                    } else {
                                        $sub = MedSubject::where('name', $t)->first();
                                    }
                                    if ($sub) {
                                        $attachIds[] = $sub->id;
                                    } else {
                                        $missing[] = $t;
                                    }
                                }

                                if (!empty($attachIds)) {
                                    // attach found subjects
                                    $doctor->subjects()->sync($attachIds);
                                }

                                // For any missing tokens, add a non-fatal warning to the preview/errors list so admin can see them
                                if (!empty($missing)) {
                                    $errors[] = ['row' => $rowNumber, 'messages' => ['بعض المواد المذكورة غير موجودة: ' . implode(', ', $missing)], 'raw' => $rowAssoc];
                                }
                            }
                    }
                    $created++;
                }
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = ['row' => $rowNumber, 'messages' => [$e->getMessage()], 'raw' => $rowAssoc];
            }
        }

        return [
            'type' => $type,
            'type_label' => $this->typeLabels[$type] ?? ucfirst($type),
            'headers' => $headers,
            'rows' => $rowsList,
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'failed' => $failed,
            'total' => count($dataRows),
            'errors' => $errors,
        ];
    }
}