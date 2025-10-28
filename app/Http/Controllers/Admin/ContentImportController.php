<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Exports\TemplateExport;
use App\Models\MedVideo;
use App\Models\MedResource;
use App\Models\ClinicalSubjectPdf;
use App\Models\MedDoctor;
use App\Models\MedSubject;
use App\Models\MedTopic;
use App\Models\MedResourceCategory;

class ContentImportController extends Controller
{
    protected $types = [
        'med_videos',
        'med_resources',
        'clinical_subject_pdfs',
    ];

    protected $typeLabels = [
        'med_videos' => 'فيديوهات المحتوى',
        'med_resources' => 'الموارد (ملفات)',
        'clinical_subject_pdfs' => 'PDFs المواد السريرية',
    ];

    public function index()
    {
        return view('admin.content_imports.index', ['types' => $this->types]);
    }

    public function show(Request $request, $type)
    {
        if (!in_array($type, $this->types)) abort(404);
        // allow re-analysis of stored preview file after DB changes
        if ($request->get('reanalyze')) {
            $preview = session('content_import_preview');
            if (!empty($preview['path'])) {
                $path = $preview['path'];
                $report = $this->processFile($path, $type, false);
                session()->put('content_import_preview', ['path' => $path, 'report' => $report]);
                session()->flash('content_import_preview_shown', true);
            }
        }
        // If the user simply visits or refreshes the page (no recent preview/reanalyze),
        // clear any lingering preview so the preview does not persist across refreshes.
        // The preview is preserved only for the immediately-redirected request from preview() or reanalyze.
        if (!session()->has('content_import_preview_shown')) {
            session()->forget('content_import_preview');
        }
        return view('admin.content_imports.upload', ['type' => $type]);
    }

    public function template($type)
    {
        if (!in_array($type, $this->types)) abort(404);

        $templates = [
            'med_videos' => ['title','youtube_url','thumbnail_url','doctor_id_or_name','subject_id_or_name','topic_id_or_name','order_index','status','published_at'],
            'med_resources' => ['title','file_url','file_size_bytes','pages_count','category_id_or_name','subject_id_or_name','topic_id_or_name','order_index','status'],
            'clinical_subject_pdfs' => ['name','file','content','order','clinical_subject_id_or_name'],
        ];

        $headers = $templates[$type] ?? [];
        $filename = $type . '-template.xlsx';
        return Excel::download(new TemplateExport($headers), $filename);
    }

    public function upload(Request $request, $type)
    {
        if (!in_array($type, $this->types)) abort(404);

        $request->validate(['file' => 'required|file|mimes:xlsx,xls']);
        $path = $request->file('file')->store('imports');
        try {
            $sheets = Excel::toArray(null, storage_path('app/' . $path));
            $rows = $sheets[0] ?? [];
        } catch (\Throwable $e) {
            return back()->withErrors(['file' => 'فشل قراءة الملف: ' . $e->getMessage()]);
        }
        if (count($rows) === 0) return back()->withErrors(['file' => 'الملف فارغ']);

        $headers = array_map(function($h){ return strtolower(trim((string)$h)); }, $rows[0]);
        $missing = [];
        // basic required header validation per type
        if ($type === 'med_videos' && !in_array('title', $headers)) $missing[] = 'title';
        if ($type === 'med_resources' && !in_array('title', $headers)) $missing[] = 'title';
        if ($type === 'clinical_subject_pdfs' && !in_array('name', $headers)) $missing[] = 'name';
        if (!empty($missing)) return back()->withErrors(['file' => 'أعمدة مفقودة: ' . implode(', ', $missing)]);

        $report = $this->processFile($path, $type, true);
        session()->flash('import_report', $report);
        // build a more detailed summary message
        $parts = [];
        $parts[] = "تم إنشاء: {$report['created']} عناصر";
        if (!empty($report['skipped_items'])) {
            $parts[] = "تم تجاوز: " . count($report['skipped_items']) . " عنصرًا (موجودة مسبقًا)";
        }
        if (!empty($report['failed'])) {
            $parts[] = "فشل: {$report['failed']}";
        }
        // include examples (limit to 6)
        if (!empty($report['created_items'])) {
            $titles = array_map(function($i){ return $i['title'] ?? ($i['youtube_url'] ?? ''); }, array_slice($report['created_items'],0,6));
            $parts[] = 'أمثلة مُستحدثة: ' . implode(', ', $titles);
        }
        if (!empty($report['skipped_items'])) {
            $skips = array_map(function($s){ return ($s['title'] ?? $s['youtube_url'] ?? '') . ' (' . ($s['reason'] ?? '') . ')'; }, array_slice($report['skipped_items'],0,6));
            $parts[] = 'أمثلة مُتجاوزة: ' . implode(', ', $skips);
        }
        if (!empty($report['errors'])) {
            $errs = [];
            foreach(array_slice($report['errors'],0,6) as $e) {
                $errs[] = '(' . ($e['row'] ?? '') . ') ' . (is_array($e['messages']) ? implode(' | ', $e['messages']) : ($e['messages'] ?? ''));
            }
            $parts[] = 'أمثلة أخطاء: ' . implode(' ; ', $errs);
        }
        session()->flash('success', implode(' — ', $parts));
        return redirect()->route('admin.content_imports.index');
    }

    public function preview(Request $request, $type)
    {
        if (!in_array($type, $this->types)) abort(404);
        $request->validate(['file' => 'required|file|mimes:xlsx,xls']);
        $path = $request->file('file')->store('imports');
        $report = $this->processFile($path, $type, false);
        session()->put('content_import_preview', ['path' => $path, 'report' => $report]);
        session()->flash('content_import_preview_shown', true);
        return redirect()->route('admin.content_imports.show', $type)->with('success', 'تم تحليل الملف — راجع المعاينة قبل التأكيد.');
    }

    public function confirm(Request $request, $type)
    {
        if (!in_array($type, $this->types)) abort(404);
        $preview = session()->get('content_import_preview');
        if (!$preview || !isset($preview['path'])) return back()->withErrors(['file' => 'لا يوجد معاينة صالحة.']);
        $path = $preview['path'];
        $previewReport = $preview['report'] ?? [];

        // if preview has relation errors, block the confirm and ask user to fix them first
        $relationErrors = array_filter($previewReport['errors'] ?? [], function($e){ return ($e['type'] ?? '') === 'relation'; });
        $hasRelationErrors = count($relationErrors) > 0;
        if ($hasRelationErrors) {
            // build a short warning with examples (rows)
            $msgs = array_map(function($e){
                $m = is_array($e['messages']) ? implode(' | ', $e['messages']) : ($e['messages'] ?? '');
                return '(' . ($e['row'] ?? '') . ') ' . $m;
            }, array_slice($relationErrors,0,6));
            $text = 'يوجد تحذيرات ربط تمنع التأكيد — صحح القيم أو أنشئ الفئات يدوياً ثم أعد المعاينة.';
            if (!empty($msgs)) $text .= ' أمثلة: ' . implode(' ; ', $msgs);
            session()->flash('warning', $text);
            return redirect()->route('admin.content_imports.show', $type);
        }

        // proceed with actual persist
        $report = $this->processFile($path, $type, true);
        session()->forget('content_import_preview');
        session()->flash('content_import_report', $report);

        // more informative summary for confirm: include examples for created/skipped/errors (limited)
        $parts = [];
        $parts[] = "تم إنشاء: {$report['created']} عناصر";
        if (!empty($report['skipped_items'])) {
            $parts[] = "تم تجاوز: " . count($report['skipped_items']) . " عنصرًا (موجودة مسبقًا)";
        }
        if (!empty($report['failed'])) {
            $parts[] = "فشل: {$report['failed']}";
        }

        // examples of created items (titles / names)
        if (!empty($report['created_items'])) {
            $titles = array_map(function($i){ return $i['title'] ?? ($i['name'] ?? ($i['youtube_url'] ?? '')); }, array_slice($report['created_items'],0,6));
            if (!empty($titles)) $parts[] = 'أمثلة مُستحدثة: ' . implode(', ', $titles);
        }

        // examples of skipped items
        if (!empty($report['skipped_items'])) {
            $skips = array_map(function($s){
                $label = $s['title'] ?? ($s['name'] ?? ($s['file'] ?? ($s['youtube_url'] ?? '')));
                return trim($label . ' (' . ($s['reason'] ?? '') . ')');
            }, array_slice($report['skipped_items'],0,6));
            if (!empty($skips)) $parts[] = 'أمثلة مُتجاوزة: ' . implode(', ', $skips);
        }

        // include a few example error messages (row + joined messages)
        if (!empty($report['errors'])) {
            $errs = [];
            foreach(array_slice($report['errors'],0,6) as $e) {
                $msgs = is_array($e['messages']) ? implode(' | ', $e['messages']) : ($e['messages'] ?? '');
                $errs[] = '(' . ($e['row'] ?? '') . ') ' . $msgs;
            }
            if (!empty($errs)) $parts[] = 'أمثلة أخطاء: ' . implode(' ; ', $errs);
        }

        // choose flash level: danger if there were failures, success otherwise
        if (!empty($report['failed'])) {
            session()->flash('danger', implode(' — ', $parts));
        } else {
            session()->flash('success', implode(' — ', $parts));
        }
        return redirect()->route('admin.content_imports.show', $type);
    }

    public function errorsExport($type)
    {
        if (!in_array($type, $this->types)) abort(404);
        $report = session('content_import_preview.report') ?? session('content_import_report');
        if (!$report || empty($report['errors'])) return back()->withErrors(['file' => 'لا توجد أخطاء للتصدير.']);
        $filename = $type . '-import-errors-' . date('Ymd_His') . '.csv';
        $callback = function() use ($report) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, ['row','messages','raw_json']);
            foreach ($report['errors'] as $e) {
                $row = $e['row'] ?? '';
                $messages = is_array($e['messages']) ? implode(' | ', $e['messages']) : (string)($e['messages'] ?? '');
                $raw = json_encode($e['raw'] ?? [], JSON_UNESCAPED_UNICODE);
                fputcsv($out, [$row, $messages, $raw]);
            }
            fclose($out);
        };
        return response()->stream($callback, 200, ['Content-Type' => 'text/csv; charset=UTF-8','Content-Disposition' => 'attachment; filename="' . $filename . '"']);
    }

    /**
     * Clear the stored preview from session and redirect back to the show page.
     */
    public function clearPreview(Request $request, $type)
    {
        if (!in_array($type, $this->types)) abort(404);
        session()->forget('content_import_preview');
        session()->forget('content_import_preview_shown');
        // optionally flash a message
        session()->flash('success', 'تم مسح معاينة الاستيراد.');
        return redirect()->route('admin.content_imports.show', $type);
    }

    /**
     * simplified validator/processor for content imports
     */
    protected function processFile(string $path, string $type, bool $persist = false)
    {
        try {
            $sheets = Excel::toArray(null, storage_path('app/' . $path));
            $rows = $sheets[0] ?? [];
        } catch (\Throwable $e) {
            return ['error' => 'فشل قراءة الملف: ' . $e->getMessage()];
        }
        if (count($rows) === 0) return ['error' => 'الملف فارغ'];
        $headers = array_map(function($h){ return strtolower(trim((string)$h)); }, $rows[0]);
        $dataRows = array_slice($rows,1);

    $created=0;$updated=0;$skipped=0;$failed=0;$errors=[];$rowsList=[];
    $created_items = [];
    $skipped_items = [];
    $failed_items = [];

        foreach ($dataRows as $index => $row) {
            $rowNumber = $index+2;
            $rowAssoc = [];
            foreach ($headers as $i=>$h) {
                $rowAssoc[$h] = isset($row[$i]) ? (is_string($row[$i])?trim($row[$i]):$row[$i]) : null;
            }
            // prepare a row entry with meta for preview (messages, relation checks)
            $rowsList[] = ['row'=>$rowNumber,'raw'=>$rowAssoc,'meta'=>['messages'=>[],'relation_ok'=>null,'relations'=>['doctor'=>null,'subject'=>null,'topic'=>null,'category'=>null]]];

            try {
                if ($type === 'med_videos') {
                    $v = Validator::make($rowAssoc, ['title'=>'required|string|max:255','youtube_url'=>'nullable|string|max:255']);
                    if ($v->fails()) {
                        $failed++;
                        $errs = $v->errors()->all();
                        // structured messages for meta
                        $structured = array_map(function($m){ return ['type'=>'validation','text'=>$m]; }, $errs);
                        $rowsList[count($rowsList)-1]['meta']['messages'] = $structured;
                        $errors[]= ['row'=>$rowNumber,'messages'=>$errs,'raw'=>$rowAssoc,'type'=>'validation'];
                        continue;
                    }

                    // resolve relations
                    $doctor = null; $subject = null; $topic = null;
                    if (!empty($rowAssoc['doctor_id_or_name'])) {
                        $d = $rowAssoc['doctor_id_or_name'];
                        $doctor = is_numeric($d) ? MedDoctor::find((int)$d) : MedDoctor::where('name',$d)->first();
                    }
                    if (!empty($rowAssoc['subject_id_or_name'])) {
                        $s = $rowAssoc['subject_id_or_name'];
                        $subject = is_numeric($s) ? MedSubject::find((int)$s) : MedSubject::where('name',$s)->first();
                    }
                    if (!empty($rowAssoc['topic_id_or_name'])) {
                        $t = $rowAssoc['topic_id_or_name'];
                        $topic = is_numeric($t) ? MedTopic::find((int)$t) : MedTopic::where('title',$t)->first();
                    }

                    // determine relation check results for preview
                    $msg = [];
                    $providedCount = 0; $foundCount = 0;
                    if (isset($rowAssoc['doctor_id_or_name']) && $rowAssoc['doctor_id_or_name'] !== '') {
                        $providedCount++;
                        if ($doctor) {
                            $foundCount++;
                            $rowsList[count($rowsList)-1]['meta']['relations']['doctor'] = true;
                            $rowAssoc['doctor_id_or_name'] = $doctor->name;
                        } else {
                            $rowsList[count($rowsList)-1]['meta']['relations']['doctor'] = false;
                            $msg[] = ['type'=>'relation','text'=>'الدكتور غير موجود: ' . $rowAssoc['doctor_id_or_name']];
                        }
                    }
                    if (isset($rowAssoc['subject_id_or_name']) && $rowAssoc['subject_id_or_name'] !== '') {
                        $providedCount++;
                        if ($subject) {
                            $foundCount++;
                            $rowsList[count($rowsList)-1]['meta']['relations']['subject'] = true;
                            $rowAssoc['subject_id_or_name'] = $subject->name;
                        } else {
                            $rowsList[count($rowsList)-1]['meta']['relations']['subject'] = false;
                            $msg[] = ['type'=>'relation','text'=>'المادة غير موجودة: ' . $rowAssoc['subject_id_or_name']];
                        }
                    }
                    if (isset($rowAssoc['topic_id_or_name']) && $rowAssoc['topic_id_or_name'] !== '') {
                        $providedCount++;
                        if ($topic) {
                            $foundCount++;
                            $rowsList[count($rowsList)-1]['meta']['relations']['topic'] = true;
                            $rowAssoc['topic_id_or_name'] = $topic->title ?? $topic->name ?? $topic;
                        } else {
                            $rowsList[count($rowsList)-1]['meta']['relations']['topic'] = false;
                            $msg[] = ['type'=>'relation','text'=>'الموضوع غير موجود: ' . $rowAssoc['topic_id_or_name']];
                        }
                    }

                    if (!$persist) {
                        $rowsList[count($rowsList)-1]['raw'] = $rowAssoc;
                        // relation_ok: null if nothing provided, true if all provided found, false if some provided and missing
                        $relation_ok = $providedCount === 0 ? null : ($foundCount === $providedCount);
                        $rowsList[count($rowsList)-1]['meta']['relation_ok'] = $relation_ok;
                        // append relation messages (structured)
                        $rowsList[count($rowsList)-1]['meta']['messages'] = $msg;
                        // also add to global errors list for export/summary (as plain texts)
                        if (!empty($msg)) {
                            $errors[] = ['row'=>$rowNumber,'messages'=>array_map(function($m){ return $m['text']; }, $msg),'raw'=>$rowAssoc,'type'=>'relation'];
                        }
                        // detect duplicates on preview (so preview shows 'existing' skips)
                        $isDuplicate = false;
                        if (!empty($rowAssoc['youtube_url'])) {
                            $exists = MedVideo::where('youtube_url', $rowAssoc['youtube_url'])->first();
                            if ($exists) $isDuplicate = true;
                        }
                        if (!$isDuplicate) {
                            // fallback to title-based match
                            $exists = MedVideo::where('title', $rowAssoc['title'])->first();
                            if ($exists) $isDuplicate = true;
                        }

                        if ($isDuplicate) {
                            $rowsList[count($rowsList)-1]['meta']['skip'] = true;
                            $rowsList[count($rowsList)-1]['meta']['skip_reason'] = 'موجود مسبقًا (تخطي)';
                            $errors[] = ['row'=>$rowNumber,'messages'=>['موجود مسبقًا (تخطي)'],'raw'=>$rowAssoc,'type'=>'skip'];
                        } else {
                            $created++; // preview count
                        }
                        continue;
                    }

                    // persist: skip existing videos (by youtube_url if present, else by title)
                    $exists = null;
                    if (!empty($rowAssoc['youtube_url'])) {
                        $exists = MedVideo::where('youtube_url', $rowAssoc['youtube_url'])->first();
                    }
                    if (!$exists) {
                        $exists = MedVideo::where('title', $rowAssoc['title'])->first();
                    }
                    if ($exists) {
                        $skipped++;
                        $skipReason = 'موجود مسبقًا';
                        $skipped_items[] = ['row'=>$rowNumber,'title'=>$rowAssoc['title'] ?? null,'youtube_url'=>$rowAssoc['youtube_url'] ?? null,'reason'=>$skipReason];
                        // mark in preview rows meta
                        $rowsList[count($rowsList)-1]['meta']['skip'] = true;
                        $rowsList[count($rowsList)-1]['meta']['skip_reason'] = $skipReason;
                        continue;
                    }

                    $video = MedVideo::create([
                        'title' => $rowAssoc['title'],
                        'youtube_url' => $rowAssoc['youtube_url'] ?? null,
                        'thumbnail_url' => $rowAssoc['thumbnail_url'] ?? null,
                        'doctor_id' => $doctor->id ?? null,
                        'subject_id' => $subject->id ?? null,
                        'topic_id' => $topic->id ?? null,
                        'order_index' => isset($rowAssoc['order_index']) && $rowAssoc['order_index'] !== '' ? (int)$rowAssoc['order_index'] : 0,
                        'status' => $rowAssoc['status'] ?? 'published',
                        'published_at' => $rowAssoc['published_at'] ?? null,
                    ]);
                    $created++;
                    $created_items[] = ['row'=>$rowNumber,'id'=>$video->id,'title'=>$video->title,'youtube_url'=>$video->youtube_url];
                }

                if ($type === 'med_resources') {
                    $v = Validator::make($rowAssoc, ['title'=>'required|string|max:255','file_url'=>'nullable|string|max:255']);
                    if ($v->fails()) {
                        $failed++;
                        $errs = $v->errors()->all();
                        $structured = array_map(function($m){ return ['type'=>'validation','text'=>$m]; }, $errs);
                        $rowsList[count($rowsList)-1]['meta']['messages'] = $structured;
                        $errors[]= ['row'=>$rowNumber,'messages'=>$errs,'raw'=>$rowAssoc,'type'=>'validation'];
                        continue;
                    }

                    $category = null; $subject=null;$topic=null;
                    if (!empty($rowAssoc['category_id_or_name'])) {
                        $c = $rowAssoc['category_id_or_name'];
                        // allowed constant category names (case-insensitive)
                        $allowedCats = ['files','notes','questions','references'];
                        if (is_numeric($c)) {
                            $category = MedResourceCategory::find((int)$c);
                        } else {
                            $category = MedResourceCategory::where('name',$c)->first();
                            // if not found but matches an allowed constant, do NOT silently accept it in preview
                            // because persist does not create categories automatically and would result in NULL id
                            if (!$category) {
                                $norm = strtolower(trim($c));
                                if (in_array($norm, $allowedCats)) {
                                    // in persist mode we keep category null (no auto-create)
                                    if ($persist) {
                                        $category = null;
                                    } else {
                                        // in preview mode: treat as missing relation so preview shows a clear warning
                                        // leave $category null and let relation-checking produce a relation message
                                        $category = null;
                                    }
                                }
                            }
                        }
                    }
                    if (!empty($rowAssoc['subject_id_or_name'])) {
                        $s = $rowAssoc['subject_id_or_name'];
                        $subject = is_numeric($s) ? MedSubject::find((int)$s) : MedSubject::where('name',$s)->first();
                    }
                    if (!empty($rowAssoc['topic_id_or_name'])) {
                        $t = $rowAssoc['topic_id_or_name'];
                        $topic = is_numeric($t) ? MedTopic::find((int)$t) : MedTopic::where('title',$t)->first();
                    }

                    if (!$persist) {
                        $msg = [];$providedCount=0;$foundCount=0;
                        if (isset($rowAssoc['category_id_or_name']) && $rowAssoc['category_id_or_name'] !== '') {
                            $providedCount++;
                            if ($category) { $foundCount++; $rowsList[count($rowsList)-1]['meta']['relations']['category'] = true; $rowAssoc['category_id_or_name'] = $category->name; }
                            else { $rowsList[count($rowsList)-1]['meta']['relations']['category'] = false; $msg[] = ['type'=>'relation','text'=>'التصنيف غير موجود: ' . $rowAssoc['category_id_or_name']]; }
                        }
                        if (isset($rowAssoc['subject_id_or_name']) && $rowAssoc['subject_id_or_name'] !== '') {
                            $providedCount++;
                            if ($subject) { $foundCount++; $rowsList[count($rowsList)-1]['meta']['relations']['subject'] = true; $rowAssoc['subject_id_or_name'] = $subject->name; }
                            else { $rowsList[count($rowsList)-1]['meta']['relations']['subject'] = false; $msg[] = ['type'=>'relation','text'=>'المادة غير موجودة: ' . $rowAssoc['subject_id_or_name']]; }
                        }
                        if (isset($rowAssoc['topic_id_or_name']) && $rowAssoc['topic_id_or_name'] !== '') {
                            $providedCount++;
                            if ($topic) { $foundCount++; $rowsList[count($rowsList)-1]['meta']['relations']['topic'] = true; $rowAssoc['topic_id_or_name'] = $topic->title ?? $topic->name ?? $topic; }
                            else { $rowsList[count($rowsList)-1]['meta']['relations']['topic'] = false; $msg[] = ['type'=>'relation','text'=>'الموضوع غير موجود: ' . $rowAssoc['topic_id_or_name']]; }
                        }
                        $rowsList[count($rowsList)-1]['raw'] = $rowAssoc;
                        $relation_ok = $providedCount === 0 ? null : ($foundCount === $providedCount);
                        $rowsList[count($rowsList)-1]['meta']['relation_ok'] = $relation_ok;
                        $rowsList[count($rowsList)-1]['meta']['messages'] = $msg;
                        if (!empty($msg)) { $errors[] = ['row'=>$rowNumber,'messages'=>array_map(function($m){return $m['text'];}, $msg),'raw'=>$rowAssoc,'type'=>'relation']; }
                        // detect duplicates on preview for resources (by file_url or title)
                        $isDuplicate = false;
                        if (!empty($rowAssoc['file_url'])) {
                            $exists = MedResource::where('file_url', $rowAssoc['file_url'])->first();
                            if ($exists) $isDuplicate = true;
                        }
                        if (!$isDuplicate) {
                            $exists = MedResource::where('title', $rowAssoc['title'])->first();
                            if ($exists) $isDuplicate = true;
                        }
                        if ($isDuplicate) {
                            $rowsList[count($rowsList)-1]['meta']['skip'] = true;
                            $rowsList[count($rowsList)-1]['meta']['skip_reason'] = 'موجود مسبقًا — تم التخطي';
                            $errors[] = ['row'=>$rowNumber,'messages'=>['موجود مسبقًا — تم التخطي'],'raw'=>$rowAssoc,'type'=>'skip'];
                        } else {
                            $created++;
                        }
                        continue;
                    }

                    // persist: skip existing resources (by file_url if present, else by title)
                    $exists = null;
                    if (!empty($rowAssoc['file_url'])) {
                        $exists = MedResource::where('file_url', $rowAssoc['file_url'])->first();
                    }
                    if (!$exists) {
                        $exists = MedResource::where('title', $rowAssoc['title'])->first();
                    }
                    if ($exists) {
                        $skipped++;
                        $skipReason = 'موجود مسبقًا';
                        $skipped_items[] = ['row'=>$rowNumber,'title'=>$rowAssoc['title'] ?? null,'file_url'=>$rowAssoc['file_url'] ?? null,'reason'=>$skipReason];
                        // mark in preview rows meta if present
                        $rowsList[count($rowsList)-1]['meta']['skip'] = true;
                        $rowsList[count($rowsList)-1]['meta']['skip_reason'] = $skipReason;
                        continue;
                    }

                    MedResource::create([
                        'title' => $rowAssoc['title'],
                        'description' => $rowAssoc['description'] ?? null,
                        'file_url' => $rowAssoc['file_url'] ?? null,
                        'file_size_bytes' => isset($rowAssoc['file_size_bytes']) && $rowAssoc['file_size_bytes'] !== '' ? (int)$rowAssoc['file_size_bytes'] : null,
                        'pages_count' => isset($rowAssoc['pages_count']) && $rowAssoc['pages_count'] !== '' ? (int)$rowAssoc['pages_count'] : null,
                        'category_id' => $category->id ?? null,
                        'subject_id' => $subject->id ?? null,
                        'topic_id' => $topic->id ?? null,
                        'order_index' => isset($rowAssoc['order_index']) && $rowAssoc['order_index'] !== '' ? (int)$rowAssoc['order_index'] : 0,
                        'status' => $rowAssoc['status'] ?? 'published',
                    ]);
                    $created++;
                }

                if ($type === 'clinical_subject_pdfs') {
                    $v = Validator::make($rowAssoc, ['name'=>'required|string|max:255','clinical_subject_id_or_name'=>'required']);
                    if ($v->fails()) {
                        $failed++;
                        $errs = $v->errors()->all();
                        $errors[]= ['row'=>$rowNumber,'messages'=>$errs,'raw'=>$rowAssoc];
                        $rowsList[count($rowsList)-1]['meta']['messages'] = $errs;
                        continue;
                    }

                    $cs = $rowAssoc['clinical_subject_id_or_name'];
                    $clinical = is_numeric($cs) ? \App\Models\ClinicalSubject::find((int)$cs) : \App\Models\ClinicalSubject::where('name',$cs)->first();
                    if (!$clinical) {
                        $failed++;
                        $errors[] = ['row'=>$rowNumber,'messages'=>['المادة السريرية غير موجودة: ' . $cs],'raw'=>$rowAssoc];
                        $rowsList[count($rowsList)-1]['meta']['messages'] = ['المادة السريرية غير موجودة: ' . $cs];
                        continue;
                    }

                    if (!$persist) {
                        $rowAssoc['clinical_subject_id_or_name'] = $clinical->name;
                        $rowsList[count($rowsList)-1]['raw'] = $rowAssoc;
                        $rowsList[count($rowsList)-1]['meta']['relation_ok'] = true;
                        $rowsList[count($rowsList)-1]['meta']['messages'] = [];
                        // detect duplicates on preview for clinical_subject_pdfs (by file or name)
                        $isDuplicate = false;
                        if (!empty($rowAssoc['file'])) {
                            $exists = ClinicalSubjectPdf::where('file', $rowAssoc['file'])->first();
                            if ($exists) $isDuplicate = true;
                        }
                        if (!$isDuplicate) {
                            $exists = ClinicalSubjectPdf::where('name', $rowAssoc['name'])->first();
                            if ($exists) $isDuplicate = true;
                        }
                        if ($isDuplicate) {
                            $rowsList[count($rowsList)-1]['meta']['skip'] = true;
                            $rowsList[count($rowsList)-1]['meta']['skip_reason'] = 'موجود مسبقًا — تم التخطي';
                            $errors[] = ['row'=>$rowNumber,'messages'=>['موجود مسبقًا — تم التخطي'],'raw'=>$rowAssoc,'type'=>'skip'];
                        } else {
                            $created++;
                        }
                        continue;
                    }

                    // persist: skip existing clinical PDFs (by file if present, else by name)
                    $exists = null;
                    if (!empty($rowAssoc['file'])) {
                        $exists = ClinicalSubjectPdf::where('file', $rowAssoc['file'])->first();
                    }
                    if (!$exists) {
                        $exists = ClinicalSubjectPdf::where('name', $rowAssoc['name'])->first();
                    }
                    if ($exists) {
                        $skipped++;
                        $skipReason = 'موجود مسبقًا';
                        $skipped_items[] = ['row'=>$rowNumber,'name'=>$rowAssoc['name'] ?? null,'file'=>$rowAssoc['file'] ?? null,'reason'=>$skipReason];
                        $rowsList[count($rowsList)-1]['meta']['skip'] = true;
                        $rowsList[count($rowsList)-1]['meta']['skip_reason'] = $skipReason;
                        continue;
                    }

                    ClinicalSubjectPdf::create([
                        'name' => $rowAssoc['name'],
                        'content' => $rowAssoc['content'] ?? null,
                        'file' => $rowAssoc['file'] ?? null,
                        'order' => isset($rowAssoc['order']) && $rowAssoc['order'] !== '' ? (int)$rowAssoc['order'] : 0,
                        'clinical_subject_id' => $clinical->id,
                    ]);
                    $created++;
                }

            } catch (\Throwable $e) {
                $failed++; $errors[] = ['row'=>$rowNumber,'messages'=>[$e->getMessage()],'raw'=>$rowAssoc];
            }
        }

        $report = [
            'type' => $type,
            'type_label' => $this->typeLabels[$type] ?? $type,
            'headers' => $headers,
            'rows' => $rowsList,
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'failed' => $failed,
            'total' => count($dataRows),
            'errors' => $errors,
            'created_items' => $created_items,
            'skipped_items' => $skipped_items,
            'failed_items' => $failed_items,
        ];

        return $report;
    }
}
