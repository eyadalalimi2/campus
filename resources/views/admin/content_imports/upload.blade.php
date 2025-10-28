@extends('admin.layouts.app')

@php
    $labels = [
        'med_videos' => 'الفيديوهات ',
        'med_resources' => 'الموارد (ملفات)',
        'clinical_subject_pdfs' => ' المواد السريرية PDF',
    ];
@endphp

@section('title','استيراد محتوى — ' . ($labels[$type] ?? ucfirst($type)) )

@section('content')
    <div class="container-fluid">
        <h1 class="mb-3">استيراد محتوى — {{ $labels[$type] ?? ucfirst($type) }}</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                {{-- Instructions card (full width) --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        @if($type === 'med_videos')
                            <div class="alert alert-info">
                                <h6 class="mb-2">إرشادات تعبئة قالب استيراد الفيديوهات</h6>
                                <p class="small mb-2">افحص الأعمدة التالية واملأ كل صف ببيانات فيديو واحد. الرجاء عدم تعديل صف الرأس أو تسميات الأعمدة.</p>
                                <div class="table-responsive mb-2">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>العمود</th>
                                                <th>الوصف</th>
                                                <th>مطلوب؟</th>
                                                <th>مثال</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>العنوان<br><small><code>title</code></small></td>
                                                <td>عنوان الفيديو كما سيظهر في التطبيق.</td>
                                                <td>نعم</td>
                                                <td>شرح تشريح القلب</td>
                                            </tr>
                                            <tr>
                                                <td>رابط YouTube<br><small><code>youtube_url</code></small></td>
                                                <td>رابط الفيديو على YouTube (يفضّل إدخاله للمطابقة التلقائية).</td>
                                                <td>لا</td>
                                                <td>https://youtube.com/...</td>
                                            </tr>
                                            <tr>
                                                <td>مسار الصورة المصغرة<br><small><code>thumbnail_url</code></small></td>
                                                <td>مسار أو رابط للصورة المصغرة داخل التخزين أو خارجي.</td>
                                                <td>لا</td>
                                                <td>uploads/videos/thumb.jpg</td>
                                            </tr>
                                            <tr>
                                                <td>دكتور<br><small><code>doctor_id_or_name</code></small></td>
                                                <td>معرّف الدكتور (ID) أو اسمه لربط الفيديو بدكتور موجود.</td>
                                                <td>لا</td>
                                                <td>1 أو د. أحمد علي</td>
                                            </tr>
                                            <tr>
                                                <td>المادة / الموضوع<br><small><code>subject_id_or_name</code> / <code>topic_id_or_name</code></small></td>
                                                <td>معرّف أو اسم المادة و/أو الموضوع لربط الفيديو بالمحتوى المناسب.</td>
                                                <td>لا</td>
                                                <td>تشريح أو 5</td>
                                            </tr>
                                            <tr>
                                                <td>الترتيب<br><small><code>order_index</code></small></td>
                                                <td>قيمة رقمية لتحديد ترتيب عرض الفيديوهات؛ قيمة أصغر تُعرض أولاً.</td>
                                                <td>لا</td>
                                                <td>10</td>
                                            </tr>
                                            <tr>
                                                <td>الحالة<br><small><code>status</code></small></td>
                                                <td>حالة عرض الفيديو: اكتب <code>published</code> أو <code>draft</code>. إن ترك الحقل فارغًا سيُفترض <code>published</code>.</td>
                                                <td>لا</td>
                                                <td>published</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <p class="small text-muted">ملاحظة: عند إدخال أعمدة تربط الدكاترة أو المواد أو المواضيع سيتم محاولة حلها إما كمعرّف رقم أو كاسم؛ القيم غير الموجودة ستظهر كتحذير في تقرير المعاينة.</p>
                            </div>
                        @elseif($type === 'med_resources')
                            <h5 class="card-title">إرشادات استيراد الموارد</h5>
                            <p class="small text-muted mb-2">كل صف يصف ملف/مصدر متعلق بالمادة. الحقل المطلوب: <code>title</code>.</p>
                            <div class="table-responsive mb-2">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light"><tr><th>العمود</th><th>الوصف</th><th>مطلوب؟</th><th>مثال</th></tr></thead>
                                    <tbody>
                                        <tr><td>العنوان<br><small><code>title</code></small></td><td>عنوان المورد.</td><td>نعم</td><td>ملف محاضرة تشريح</td></tr>
                                        <tr><td>رابط/مسار الملف<br><small><code>file_url</code></small></td><td>رابط مباشر أو مسار داخل التخزين.</td><td>لا</td><td>uploads/resources/anatomy.pdf</td></tr>
                                        <tr><td>حجم الملف بالبايت<br><small><code>file_size_bytes</code></small></td><td>حجم الملف إذا توافر (اختياري).</td><td>لا</td><td>1240000</td></tr>
                                        <tr><td>عدد الصفحات<br><small><code>pages_count</code></small></td><td>عدد صفحات الـ PDF إن وُجد.</td><td>لا</td><td>48</td></tr>
                                        <tr><td>التصنيف<br><small><code>category_id_or_name</code></small></td><td>معرّف أو اسم تصنيف الملف.</td><td>لا</td><td>محاضرات</td></tr>
                                    </tbody></table>
                            </div>
                        @elseif($type === 'clinical_subject_pdfs')
                            <h5 class="card-title">إرشادات استيراد PDF المواد السريرية</h5>
                            <p class="small text-muted mb-2">كل صف يصف ملف PDF مرتبط بمادة سريرية. الحقول المطلوبة: <code>name</code> و<code>clinical_subject_id_or_name</code>.</p>
                            <div class="table-responsive mb-2">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light"><tr><th>العمود</th><th>الوصف</th><th>مطلوب؟</th><th>مثال</th></tr></thead>
                                    <tbody>
                                        <tr><td>الاسم<br><small><code>name</code></small></td><td>اسم الملف كما سيظهر في التطبيق.</td><td>نعم</td><td>ملخص جراحة الفم</td></tr>
                                        <tr><td>مسار/رابط الملف<br><small><code>file</code></small></td><td>رابط أو مسار الملف داخل التخزين.</td><td>نعم</td><td>uploads/clinical_pdfs/guide.pdf</td></tr>
                                        <tr><td>المحتوى (وصف)<br><small><code>content</code></small></td><td>وصف موجز للمحتوى.</td><td>لا</td><td>ملاحظات مساعدة للمذاكرة</td></tr>
                                        <tr><td>الترتيب<br><small><code>order</code></small></td><td>قيمة رقمية لترتيب العرض.</td><td>لا</td><td>1</td></tr>
                                        <tr><td>المادة السريرية<br><small><code>clinical_subject_id_or_name</code></small></td><td>معرّف المادة أو اسمها لربط الملف.</td><td>نعم</td><td>تشريح سريري</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <p class="text-muted small mb-0">اتبع الإرشادات أعلاه بعناية، وحمّل الملف بصيغة Excel (.xlsx أو .xls). تأكد من أن أسماء الأعمدة لم تُعدّل.</p>
                    </div>
                </div>

                {{-- Upload card (full width) --}}
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <h5 class="card-title">رفع الملف</h5>
                        <p class="card-text small text-muted">اختر ملف Excel ثم اضغط "معاينة الملف" للفحص قبل التأكيد.</p>

                        <form id="importForm" method="post" enctype="multipart/form-data" action="{{ route('admin.content_imports.preview', $type) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">اختر ملف Excel (.xlsx أو .xls)</label>
                                <div class="input-group">
                                    <input type="file" name="file" id="fileInput" class="form-control" accept=".xlsx, .xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                    <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('fileInput').click();">استعراض</button>
                                </div>
                                <div class="form-text mt-1">الملف المختار: <span id="selectedFileName" class="fw-semibold">لا يوجد ملف</span></div>
                            </div>

                            <div class="d-flex align-items-center">
                                <button id="previewBtn" class="btn btn-primary me-2" type="submit" disabled>معاينة الملف</button>
                                <a href="#" class="btn btn-outline-secondary me-2" id="clearSelection">مسح الاختيار</a>
                                <a href="{{ route('admin.content_imports.template', $type) }}" class="btn btn-outline-secondary">تحميل القالب</a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Preview / errors card will render below if session contains preview --}}
                @php $p = session('content_import_preview'); @endphp
                @if (!empty($p) && ($p['report']['type'] ?? '') === $type)
                    @php $r = $p['report']; @endphp
                    <hr>
                    <h6>معاينة الاستيراد — {{ $r['type_label'] ?? ($labels[$type] ?? ucfirst($type)) }}</h6>
                    <div class="mb-2">سيتم إنشاء <strong>{{ $r['created'] }}</strong> — سيتم تخطي
                        <strong>{{ $r['skipped'] }}</strong> — فشل <strong>{{ $r['failed'] }}</strong> من إجمالي
                        <strong>{{ $r['total'] }}</strong>
                    </div>

                    @php
                        $errorRows = collect($r['errors'] ?? [])
                            ->pluck('row')
                            ->all();
                        $showRows = $r['rows'] ?? [];
                        $rowsCount = count($showRows);
                        $maxDisplay = 500;
                    @endphp

                    <div class="mb-3">
                        <form method="post" action="{{ route('admin.content_imports.confirm', $type) }}">
                            @csrf
                            <button class="btn btn-success">تأكيد الاستيراد</button>
                            <a href="{{ route('admin.content_imports.errors_export', $type) }}"
                                class="btn btn-outline-danger ms-2">تصدير الأخطاء</a>
                        </form>
                    </div>

                    <h6 class="mt-3">معاينة البيانات (عرض جزءي)</h6>
                    @php
                        $page = max(1, (int)request()->get('preview_page', 1));
                        $perPage = 50;
                        $totalRows = $r['total'] ?? count($r['rows'] ?? []);
                        $totalPages = max(1, (int)ceil($totalRows / $perPage));
                        $offset = ($page - 1) * $perPage;
                        $displayRows = array_slice($r['rows'] ?? [], $offset, $perPage);

                        // hide these technical columns from the preview table
                        $skipCols = ['thumbnail_url','youtube_url','order_index','status'];
                        $visibleHeaders = array_filter($r['headers'] ?? [], function($h) use ($skipCols) {
                            $k = strtolower(str_replace(' ', '_', trim($h)));
                            return !in_array($k, $skipCols);
                        });
                        // if visibleHeaders is empty fallback to all headers
                        if (empty($visibleHeaders)) {
                            $visibleHeaders = $r['headers'] ?? [];
                        }
                    @endphp

                    <div class="table-responsive" style="max-height:420px;overflow:auto;">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    @foreach($visibleHeaders as $h)
                                        <th>{{ $h }}</th>
                                    @endforeach
                                    <th style="min-width:160px">الروابط</th>
                                    <th style="min-width:200px">أخطاء / ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($displayRows as $row)
                                    @php
                                        $meta = $row['meta'] ?? ['messages'=>[],'relation_ok'=>null,'relations'=>[]];
                                        $messages = $meta['messages'] ?? [];
                                        $hasValidation = count(array_filter($messages, function($m){ return ($m['type'] ?? '') === 'validation'; })) > 0;
                                        $hasRelationIssues = count(array_filter($messages, function($m){ return ($m['type'] ?? '') === 'relation'; })) > 0;
                                        $rowClass = $hasValidation ? 'table-danger' : ($hasRelationIssues ? 'table-warning' : '');
                                        $relations = $meta['relations'] ?? [];
                                    @endphp
                                    <tr class="{{ $rowClass }}">
                                        <td>{{ $row['row'] ?? '' }}</td>
                                        @foreach($visibleHeaders as $h)
                                            <td>{{ is_scalar($row['raw'][$h] ?? null) ? ($row['raw'][$h] ?? '') : json_encode($row['raw'][$h] ?? '') }}</td>
                                        @endforeach
                                        <td style="white-space:nowrap;">
                                            @if($type === 'med_videos')
                                                {{-- doctor badge --}}
                                                @php $dr = $relations['doctor'] ?? null; @endphp
                                                @if($dr === true)
                                                    <span class="badge bg-success me-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16"><path d="M13.854 3.646a.5.5 0 0 1 0 .708L6.707 11.5a.5.5 0 0 1-.708 0L2.146 7.646a.5.5 0 1 1 .708-.708L6.25 10.146l6.896-6.5a.5.5 0 0 1 .708 0z"/></svg>
                                                        دكتور
                                                    </span>
                                                @elseif($dr === false)
                                                    <span class="badge bg-warning me-1 text-dark">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg>
                                                        دكتور
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary me-1">دكتور</span>
                                                @endif

                                                {{-- subject badge --}}
                                                @php $su = $relations['subject'] ?? null; @endphp
                                                @if($su === true)
                                                    <span class="badge bg-success me-1">المادة</span>
                                                @elseif($su === false)
                                                    <span class="badge bg-warning me-1 text-dark">المادة</span>
                                                @else
                                                    <span class="badge bg-secondary me-1">المادة</span>
                                                @endif

                                                {{-- topic badge --}}
                                                @php $to = $relations['topic'] ?? null; @endphp
                                                @if($to === true)
                                                    <span class="badge bg-success me-1">الموضوع</span>
                                                @elseif($to === false)
                                                    <span class="badge bg-warning me-1 text-dark">الموضوع</span>
                                                @else
                                                    <span class="badge bg-secondary me-1">الموضوع</span>
                                                @endif

                                            @elseif($type === 'med_resources')
                                                @php $cat = $relations['category'] ?? null; $su = $relations['subject'] ?? null; $to = $relations['topic'] ?? null; @endphp
                                                @if($cat === true)<span class="badge bg-success me-1">تصنيف</span>@elseif($cat === false)<span class="badge bg-warning me-1 text-dark">تصنيف</span>@else<span class="badge bg-secondary me-1">تصنيف</span>@endif
                                                @if($su === true)<span class="badge bg-success me-1">المادة</span>@elseif($su === false)<span class="badge bg-warning me-1 text-dark">المادة</span>@else<span class="badge bg-secondary me-1">المادة</span>@endif
                                                @if($to === true)<span class="badge bg-success me-1">الموضوع</span>@elseif($to === false)<span class="badge bg-warning me-1 text-dark">الموضوع</span>@else<span class="badge bg-secondary me-1">الموضوع</span>@endif

                                            @elseif($type === 'clinical_subject_pdfs')
                                                @if(($meta['relation_ok'] ?? null) === true)
                                                    <span class="badge bg-success">المادة السريرية</span>
                                                @elseif(($meta['relation_ok'] ?? null) === false)
                                                    <span class="badge bg-warning text-dark">المادة السريرية</span>
                                                @else
                                                    <span class="badge bg-secondary">المادة السريرية</span>
                                                @endif
                                            @endif
                                            @if(!empty($meta['skip']))
                                                <span class="badge bg-warning me-1 text-dark">مكرر — تم التخطي</span>
                                            @endif
                                        </td>
                                        <td>
                                                @php $skipReason = $meta['skip_reason'] ?? null; @endphp
                                                @if(!empty($messages) || $skipReason)
                                                    @foreach($messages as $m)
                                                        <div class="small">• {{ $m['text'] ?? $m }}</div>
                                                    @endforeach
                                                    @if($skipReason)
                                                        <div class="small text-info">• {{ $skipReason }}</div>
                                                    @endif
                                                @else
                                                    <div class="small text-muted">لا توجد ملاحظات</div>
                                                @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- pagination controls --}}
                    <div class="mt-2 d-flex align-items-center">
                        <nav aria-label="Preview pagination">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ route('admin.content_imports.show', $type) }}?preview_page={{ max(1, $page-1) }}">&laquo;</a>
                                </li>
                                @for($p=1;$p<=$totalPages;$p++)
                                    <li class="page-item {{ $p === $page ? 'active' : '' }}"><a class="page-link" href="{{ route('admin.content_imports.show', $type) }}?preview_page={{ $p }}">{{ $p }}</a></li>
                                @endfor
                                <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ route('admin.content_imports.show', $type) }}?preview_page={{ min($totalPages, $page+1) }}">&raquo;</a>
                                </li>
                            </ul>
                        </nav>
                        <div class="ms-3 small text-muted">صفحة {{ $page }} من {{ $totalPages }} — إجمالي {{ $totalRows }} صف</div>
                    </div>

                    <div class="mt-3 d-flex">
                        <form method="post" action="{{ route('admin.content_imports.confirm', $type) }}">
                            @csrf
                            <button class="btn btn-success me-2">تأكيد الاستيراد</button>
                        </form>

                        {{-- removed explicit "مسح المعاينة" button: preview will disappear on refresh or after confirm --}}

                        <a href="{{ route('admin.content_imports.errors_export', $type) }}" class="btn btn-outline-secondary me-2">تنزيل أخطاء الاستيراد</a>
                        <a href="{{ route('admin.content_imports.show', $type) }}?reanalyze=1" class="btn btn-outline-primary me-2">إعادة تحليل</a>
                    </div>
                        <div class="mt-2 small text-muted">ملاحظة: عند الضغط على "تأكيد الاستيراد" سيتم حفظ السجلات في قاعدة البيانات وسيتم إزالة معاينة الجلسة. تأكد من مراجعة الصفوف المميزة بالأحمر (أخطاء التحقق) أو الأصفر (أخطاء الربط) وتصحيحها قبل التأكيد.</div>

                    @if ($rowsCount > $maxDisplay)
                        <div class="small text-muted mt-2">تم عرض أول {{ $maxDisplay }} صفوف من أصل
                            {{ $rowsCount }}. استخدم تصدير الأخطاء أو تحقق من الملف الأصلي لمراجعة الباقي.</div>
                    @endif

                    <hr>

                    @php
                        $postReport = session('content_import_report');
                    @endphp

                    {{-- Preview errors (show during preview) --}}
                    @if(!empty($r['errors']))
                        <div class="card mt-3 border-danger">
                            <div class="card-body">
                                <h6 class="card-title">قائمة الأخطاء والتحذيرات (معاينة)</h6>
                                <div class="table-responsive" style="max-height:360px; overflow:auto;">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>سطر</th>
                                                <th>النوع</th>
                                                <th>الرسائل</th>
                                                <th>بيانات خام</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($r['errors'] as $err)
                                                <tr>
                                                    <td>{{ $err['row'] ?? '' }}</td>
                                                    <td>{{ $err['type'] ?? 'غير محدد' }}</td>
                                                    <td>
                                                        @if(is_array($err['messages']))
                                                            @foreach($err['messages'] as $m)
                                                                <div class="small">• {{ $m }}</div>
                                                            @endforeach
                                                        @else
                                                            <div class="small">{{ $err['messages'] }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="small text-muted">{{ json_encode($err['raw'] ?? [], JSON_UNESCAPED_UNICODE) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Post-confirm report (show after confirm) --}}
                    @if(!empty($postReport) && !empty($postReport['errors']))
                        <div class="card mt-3 border-danger">
                            <div class="card-body">
                                <h6 class="card-title">قائمة الأخطاء والتحذيرات (نتيجة الاستيراد)</h6>
                                <div class="table-responsive" style="max-height:360px; overflow:auto;">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>سطر</th>
                                                <th>النوع</th>
                                                <th>الرسائل</th>
                                                <th>بيانات خام</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($postReport['errors'] as $err)
                                                <tr>
                                                    <td>{{ $err['row'] ?? '' }}</td>
                                                    <td>{{ $err['type'] ?? 'غير محدد' }}</td>
                                                    <td>
                                                        @if(is_array($err['messages']))
                                                            @foreach($err['messages'] as $m)
                                                                <div class="small">• {{ $m }}</div>
                                                            @endforeach
                                                        @else
                                                            <div class="small">{{ $err['messages'] }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="small text-muted">{{ json_encode($err['raw'] ?? [], JSON_UNESCAPED_UNICODE) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        @push('scripts')
            <script>
                (function() {
                    const fileInput = document.getElementById('fileInput');
                    const clientArea = document.getElementById('clientPreviewArea');
                    const previewBtn = document.getElementById('previewBtn');
                    const selectedName = document.getElementById('selectedFileName');
                    const clearBtn = document.getElementById('clearSelection');

                    if (!fileInput) return;

                    function onFileChange(el) {
                        const f = el.files[0];
                        if (!f) {
                            selectedName.textContent = 'لا يوجد ملف';
                            previewBtn.disabled = true;
                            clientArea.innerHTML = '';
                            return;
                        }
                        selectedName.textContent = f.name;
                        previewBtn.disabled = false;
                    }

                    fileInput.addEventListener('change', function(){ onFileChange(this); });
                    clearBtn && clearBtn.addEventListener('click', function(e){ e.preventDefault(); fileInput.value = ''; onFileChange(fileInput); });
                })();
            </script>
        @endpush

    </div>
@endsection
