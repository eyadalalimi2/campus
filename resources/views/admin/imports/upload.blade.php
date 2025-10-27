@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        @php
            $labels = [
                'universities' => 'الجامعات',
                'branches' => 'الفروع',
                'colleges' => 'الكليات',
                'majors' => 'التخصصات',
                'med_devices' => 'أجهزة طبية',
                'med_subjects' => 'مواد طبية',
                'med_topics' => 'مواضيع طبية',
                'med_doctors' => 'دكاترة',
            ];
        @endphp
        <h1 class="mb-3">استيراد — {{ $labels[$type] ?? ucfirst($type) }}</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <p>يمكنك تحميل ملف Excel يحتوي على البيانات. لتحميل قالب جاهز اضغط على زر "تحميل القالب".</p>

                {{-- Per-type guidance --}}
                @if ($type === 'universities')
                    <div class="alert alert-info">
                        <h6 class="mb-2">إرشادات تعبئة قالب استيراد الجامعات</h6>
                        <p class="small mb-2">افحص الأعمدة التالية واملأ كل صف ببيانات جامعة واحدة. الرجاء عدم تعديل صف
                            الرأس أو تسميات الأعمدة.</p>
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
                                        <td>الاسم<br><small><code>name</code></small></td>
                                        <td>اسم الجامعة كما سيظهر في التطبيق.</td>
                                        <td>نعم</td>
                                        <td>جامعة صنعاء</td>
                                    </tr>
                                    <tr>
                                        <td>العنوان<br><small><code>address</code></small></td>
                                        <td>العنوان أو مقر الجامعة (اختياري).</td>
                                        <td>لا</td>
                                        <td>شارع الستين، جسر مذبح</td>
                                    </tr>
                                    <tr>
                                        <td>الهاتف<br><small><code>phone</code></small></td>
                                        <td>رقم هاتف الجامعة (اختياري).</td>
                                        <td>لا</td>
                                        <td>+967123456789</td>
                                    </tr>
                                    <tr>
                                        <td>مفعل<br><small><code>is_active</code></small></td>
                                        <td>حالة الجامعة: اكتب 1 للتفعيل، 0 للتعطيل. إن ترك الحقل فارغًا سيتم اعتباره 1.
                                        </td>
                                        <td>لا</td>
                                        <td>1</td>
                                    </tr>
                                    <tr>
                                        <td>الشعار<br><small><code>logo</code></small></td>
                                        <td>شعار الجامعة (بعد الانتهاء من استيراد الجامعات ارفع الشعار عبر لوحة التحكم).</td>
                                        <td>لا</td>
                                        <td>
                                            الانتقال لصفحه الجامعات
                                            <a href="{{ route('admin.universities.index') }}" class="btn btn-sm btn-outline-primary">
                                                من هنا
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($type === 'branches')
                    <div class="alert alert-info">
                        <h6 class="mb-2">إرشادات تعبئة قالب استيراد الفروع</h6>
                        <p class="small mb-2">كل صف يصف فرعاً مرتبطاً بجامعة. يمكنك استخدام معرف الجامعة (ID) أو اسم
                            الجامعة لربط الفرع بجامعته.</p>
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
                                        <td>معرّف الجامعة<br><small><code>university_id</code></small></td>
                                        <td>معرّف الجامعة (ID) — يفضّل استخدام الـ ID لربط الفرع بجامعة موجودة، لكن يمكن
                                            إدخال اسم الجامعة أيضاً.</td>
                                        <td>نعم</td>
                                        <td>1 أو جامعة صنعاء</td>
                                    </tr>
                                    <tr>
                                        <td>الاسم<br><small><code>name</code></small></td>
                                        <td>اسم الفرع كما سيظهر في التطبيق.</td>
                                        <td>نعم</td>
                                        <td>الفرع الرئيسي</td>
                                    </tr>
                                    <tr>
                                        <td>العنوان<br><small><code>address</code></small></td>
                                        <td>موقع أو عنوان الفرع (اختياري).</td>
                                        <td>لا</td>
                                        <td>العنوان التفصيلي</td>
                                    </tr>
                                    <tr>
                                        <td>الهاتف<br><small><code>phone</code></small></td>
                                        <td>رقم هاتف الفرع (اختياري).</td>
                                        <td>لا</td>
                                        <td>+967123456789</td>
                                    </tr>
                                    <tr>
                                        <td>البريد الإلكتروني<br><small><code>email</code></small></td>
                                        <td>بريد إلكتروني للتواصل مع الفرع (اختياري).</td>
                                        <td>لا</td>
                                        <td>branch@example.com</td>
                                    </tr>
                                    <tr>
                                        <td>مفعل<br><small><code>is_active</code></small></td>
                                        <td>حالة الفرع: اكتب 1 للتفعيل، 0 للتعطيل. إن ترك الحقل فارغًا سيتم اعتباره 1.</td>
                                        <td>لا</td>
                                        <td>1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($type === 'colleges')
                    <div class="alert alert-info">
                        <h6 class="mb-2">إرشادات تعبئة قالب استيراد الكليات</h6>
                        <p class="small mb-2">كل صف يصف كلية ترتبط بفرع (Branch). استخدم معرف الفرع أو اسمه لربط الكلية
                            بفرعها.</p>
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
                                        <td>معرّف الجامعة<br><small><code>university_id</code></small></td>
                                        <td>معرّف الجامعة (ID) لربط الكلية بمؤسستها.</td>
                                        <td>نعم</td>
                                        <td>1</td>
                                    </tr>
                                    <tr>
                                        <td>معرّف الفرع<br><small><code>branch_id</code></small></td>
                                        <td>معرّف الفرع (ID) الذي تنتمي إليه الكلية.</td>
                                        <td>نعم</td>
                                        <td>5</td>
                                    </tr>
                                    <tr>
                                        <td>الاسم<br><small><code>name</code></small></td>
                                        <td>اسم الكلية كما سيظهر في التطبيق.</td>
                                        <td>نعم</td>
                                        <td>كلية الطب</td>
                                    </tr>
                                    <tr>
                                        <td>مفعل<br><small><code>is_active</code></small></td>
                                        <td>حالة الكلية: اكتب 1 للتفعيل، 0 للتعطيل. إن ترك الحقل فارغًا سيتم اعتباره 1.</td>
                                        <td>لا</td>
                                        <td>1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($type === 'majors')
                    <div class="alert alert-info">
                        <h6 class="mb-2">إرشادات تعبئة قالب استيراد التخصصات</h6>
                        <p class="small mb-2">كل صف يصف تخصصًا أكاديميًا مرتبطًا بكلية. يمكنك ربط التخصص بالكلية
                            باستخدام المعرف أو الاسم.</p>
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
                                        <td>معرّف الكلية<br><small><code>college_id</code></small></td>
                                        <td>معرّف الكلية (ID) التي ينتمي إليها التخصص.</td>
                                        <td>نعم</td>
                                        <td>12</td>
                                    </tr>
                                    <tr>
                                        <td>الاسم<br><small><code>name</code></small></td>
                                        <td>اسم التخصص كما سيظهر في التطبيق.</td>
                                        <td>نعم</td>
                                        <td>طب أسنان</td>
                                    </tr>
                                    <tr>
                                        <td>مفعل<br><small><code>is_active</code></small></td>
                                        <td>حالة التخصص: اكتب 1 للتفعيل، 0 للتعطيل. إن ترك الحقل فارغًا سيتم اعتباره 1.</td>
                                        <td>لا</td>
                                        <td>1</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($type === 'med_devices')
                    <div class="alert alert-info">
                        <h6 class="mb-2">إرشادات استيراد الأجهزة الطبية</h6>
                        <p class="small mb-2">كل صف يصف جهازًا طبيًا. الحقل المطلوب الوحيد هو <code>name</code>.</p>
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
                                        <td>الاسم<br><small><code>name</code></small></td>
                                        <td>اسم الجهاز كما سيظهر في التطبيق.</td>
                                        <td>نعم</td>
                                        <td>جهاز تخطيط القلب</td>
                                    </tr>
                                    <tr>
                                        <td>الصوره<br><small><code>image</code></small></td>
                                        <td>يتم رفعها بواسطه لوحه التحكم</td>
                                        <td>لا</td>
                                        <td>
                                            الانتقال لصفحه الاجهزة الطبية
                                            <a href="{{ route('admin.med_devices.index') }}" class="btn btn-sm btn-outline-primary">
                                                من هنا
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>المواد<br><small><code>device_ids</code></small></td>
                                        <td>يتم ربط الاجهزة الطبية بالمادة بواسطة لوحة التحكم</td>
                                        <td>لا</td>
                                       <td>
                                            الانتقال لصفحه الاجهزة الطبية
                                            <a href="{{ route('admin.med_devices.index') }}" class="btn btn-sm btn-outline-primary">
                                                من هنا
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($type === 'med_subjects')
                    <div class="alert alert-info">
                        <h6 class="mb-2">إرشادات استيراد المواد الطبية</h6>
                        <p class="small mb-2">كل صف يصف مادة طبية (subject). الحقول المدعومة: <code>name</code> (مطلوب)، <code>scope</code> (النطاق، اختياري)، <code>status</code> (الحالة، اختياري).</p>
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
                                        <td>الاسم<br><small><code>name</code></small></td>
                                        <td>اسم المادة.</td>
                                        <td>نعم</td>
                                        <td>تشريح</td>
                                    </tr>
                                    <tr>
                                        <td>النطاق<br><small><code>scope</code></small></td>
                                        <td>نطاق المادة أو القسم/البرنامج الذي تنتمي إليه (اختياري).</td>
                                        <td>لا</td>
                                        <td>سريري</td>
                                    </tr>
                                    <tr>
                                        <td>الحالة<br><small><code>status</code></small></td>
                                        <td>حالة المادة: اكتب <code>published</code> أو <code>draft</code> إن رغبت.</td>
                                        <td>لا</td>
                                        <td>published</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($type === 'med_topics')
                    <div class="alert alert-info">
                        <h6 class="mb-2">إرشادات استيراد مواضيع طبية</h6>
                        <p class="small mb-2">كل صف يصف موضوعًا فرعيًا مرتبطًا بمادة. الحقول المطلوبة: <code>subject_id</code> (معرّف المادة)، <code>name</code> (اسم الموضوع). الحقل <code>status</code> اختياري.</p>
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
                                        <td>معرّف المادة<br><small><code>subject_id</code></small></td>
                                        <td>معرّف المادة (ID) التي ينتمي إليها الموضوع.</td>
                                        <td>نعم</td>
                                        <td>12</td>
                                    </tr>
                                    <tr>
                                        <td>الاسم<br><small><code>name</code></small></td>
                                        <td>اسم/عنوان الموضوع.</td>
                                        <td>نعم</td>
                                        <td>الجهاز الدوري</td>
                                    </tr>
                                    <tr>
                                        <td>الحالة<br><small><code>status</code></small></td>
                                        <td>حالة الموضوع (اختياري).</td>
                                        <td>لا</td>
                                        <td>published</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($type === 'med_doctors')
                    <div class="alert alert-info">
                        <h6 class="mb-2">إرشادات استيراد الدكاترة</h6>
                        <p class="small mb-2">كل صف يصف دكتورًا. الحقول المدعومة الآن: <code>name</code> (مطلوب) و<code>status</code> (اختياري).</p>
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
                                        <td>الاسم<br><small><code>name</code></small></td>
                                        <td>اسم الدكتور كما سيظهر في التطبيق.</td>
                                        <td>نعم</td>
                                        <td>د. أحمد علي</td>
                                    </tr>
                                    <tr>
                                        <td>الحالة<br><small><code>status</code></small></td>
                                        <td>حالة العرض (اختياري). أمثلة: <code>published</code> أو <code>draft</code>.</td>
                                        <td>لا</td>
                                        <td>published</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="small text-muted">ملاحظة: ربط الدكاترة بالمواد عبر عمود <code>subject_ids</code> لم يعد مدعوماً في الاستيراد المباشر؛ يمكنك ربط المواد لاحقاً من خلال واجهة إدارة الدكاترة.</p>
                    </div>
                @endif

                <div class="row">
                    

                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title">رفع الملف</h5>
                                <p class="card-text small text-muted">اختر ملف Excel ثم اضغط "معاينة الملف" للفحص قبل
                                    التأكيد.</p>

                                <form id="importForm" method="post" enctype="multipart/form-data"
                                    action="{{ route('admin.imports.preview', $type) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">اختر ملف Excel (.xlsx أو .xls)</label>
                                        <div class="input-group">
                                            <input type="file" name="file" id="fileInput" class="form-control"
                                                accept=".xlsx, .xls, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                            <button type="button" class="btn btn-outline-secondary"
                                                onclick="document.getElementById('fileInput').click();">استعراض</button>
                                        </div>
                                        <div class="form-text mt-1">الملف المختار: <span id="selectedFileName"
                                                class="fw-semibold">لا يوجد ملف</span></div>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <button id="previewBtn" class="btn btn-primary me-2" type="submit" disabled>معاينة
                                            الملف</button>
                                        <a href="#" class="btn btn-outline-secondary" id="clearSelection">مسح
                                            الاختيار</a>
                                    </div>
                                </form>

                                <div id="clientPreviewArea" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <a href="{{ route('admin.imports.template', $type) }}" class="btn btn-outline-secondary">تحميل
                                القالب</a>
                        </div>

                        <div id="instructionsArea">
                            <p class="text-muted small">اتبع الإرشادات أعلاه بعناية، وحمّل الملف بصيغة Excel (.xlsx أو
                                .xls). تأكد من أن أسماء الأعمدة لم تُعدّل.</p>
                        </div>
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
                                const name = f.name.toLowerCase();
                                if (name.endsWith('.xlsx') || name.endsWith('.xls')) {
                                    clientArea.innerHTML =
                                        '<div class="alert alert-info">تم اختيار ملف Excel. سيتم إجراء المعاينة الكاملة على الخادم عند الضغط على "معاينة الملف".</div>';
                                    previewBtn.disabled = false;
                                } else {
                                    clientArea.innerHTML =
                                        '<div class="alert alert-danger">يرجى رفع ملف Excel فقط (.xlsx أو .xls).</div>';
                                    previewBtn.disabled = true;
                                    el.value = '';
                                    selectedName.textContent = 'لا يوجد ملف';
                                }
                            }

                            fileInput.addEventListener('change', function() {
                                onFileChange(this);
                            });
                            if (clearBtn) clearBtn.addEventListener('click', function(e) {
                                e.preventDefault();
                                if (fileInput) {
                                    fileInput.value = '';
                                    onFileChange(fileInput);
                                }
                            });
                        })();
                    </script>
                @endpush

                {{-- Server-side preview (one-time) --}}
                @php $p = session('import_preview'); @endphp
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
                        <form method="post" action="{{ route('admin.imports.confirm', $type) }}">
                            @csrf
                            <button class="btn btn-success">تأكيد الاستيراد</button>
                            <a href="{{ route('admin.imports.errors_export', $type) }}"
                                class="btn btn-outline-danger ms-2">تصدير الأخطاء</a>
                        </form>
                    </div>

                    <h6 class="mt-3">معاينة البيانات (عرض أول {{ $maxDisplay }} من {{ $rowsCount }})</h6>
                    <div class="table-responsive" style="max-height:360px; overflow:auto;">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px">سطر</th>
                                    @foreach ($r['headers'] ?? [] as $h)
                                        <th>{{ $h }}</th>
                                    @endforeach
                                    @if(($r['type'] ?? '') === 'colleges')
                                        <th>حالة الارتباط</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (array_slice($showRows, 0, $maxDisplay) as $row)
                                    @php $isError = in_array($row['row'], $errorRows); @endphp
                                    <tr class="{{ $isError ? 'table-danger' : '' }}">
                                        <td>{{ $row['row'] }}</td>
                                        @foreach ($r['headers'] ?? [] as $h)
                                            <td>{{ $row['raw'][$h] ?? '' }}</td>
                                        @endforeach
                                        @if(($r['type'] ?? '') === 'colleges')
                                            @php $rel = $row['meta']['relation_ok'] ?? null; @endphp
                                            <td>
                                                @if($rel === true)
                                                    <span class="text-success">✔ مرتبط</span>
                                                @elseif($rel === false)
                                                    <span class="text-danger">✖ غير مرتبط</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($rowsCount > $maxDisplay)
                        <div class="small text-muted mt-2">تم عرض أول {{ $maxDisplay }} صفوف من أصل
                            {{ $rowsCount }}. استخدم تصدير الأخطاء أو تحقق من الملف الأصلي لمراجعة الباقي.</div>
                    @endif

                    <hr>

                    <div class="table-responsive" style="max-height:360px; overflow:auto;">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>سطر</th>
                                    <th>حالة</th>
                                    <th>الرسائل</th>
                                    <th>البيانات (JSON)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($r['errors'] as $err)
                                    <tr class="{{ empty($err['messages']) ? 'table-success' : 'table-danger' }}">
                                        <td>{{ $err['row'] }}</td>
                                        <td>
                                            @if (empty($err['messages']))
                                                صالح
                                            @else
                                                خطأ
                                            @endif
                                        </td>
                                        <td>
                                            @if (!empty($err['messages']))
                                                <ul class="mb-0 small">
                                                    @foreach ($err['messages'] as $m)
                                                        <li>{{ $m }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td>
                                            <pre class="mb-0 small">{{ json_encode($err['raw'] ?? [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection
<hr>
