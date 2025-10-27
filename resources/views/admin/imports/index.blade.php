@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">استيراد البيانات عبر Excel</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('import_report'))
        @php $r = session('import_report'); @endphp
        <div class="card mb-3">
                <div class="card-body">
                <h5 class="mb-2">ملخّص آخر استيراد — {{ $r['type_label'] ?? $r['type'] ?? '' }}</h5>
                <div class="row">
                    <div class="col-md-3"><strong>إجمالي الصفوف:</strong> {{ $r['total'] ?? 0 }}</div>
                    <div class="col-md-3"><strong>تم الإنشاء:</strong> {{ $r['created'] ?? 0 }}</div>
                    <div class="col-md-3"><strong>تم التخطي:</strong> {{ $r['skipped'] ?? 0 }}</div>
                    <div class="col-md-3"><strong>فشل:</strong> {{ $r['failed'] ?? 0 }}</div>
                </div>

                @if(!empty($r['errors']))
                    <hr>
                    <div class="mb-2">تفاصيل الأخطاء (سطر — رسائل — بيانات السطر):</div>
                    <div class="table-responsive" style="max-height:320px; overflow:auto;">
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="table-light"><tr><th>سطر</th><th>الرسائل</th><th>البيانات</th></tr></thead>
                            <tbody>
                                @foreach($r['errors'] as $err)
                                    <tr>
                                        <td class="align-middle">{{ $err['row'] ?? '-' }}</td>
                                        <td class="align-middle text-start"><ul class="mb-0">
                                            @foreach((array)($err['messages'] ?? []) as $m)
                                                <li>{{ $m }}</li>
                                            @endforeach
                                        </ul></td>
                                        <td class="align-middle"><pre class="mb-0 small">{{ json_encode($err['raw'] ?? [], JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) }}</pre></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <p>اختر نوع البيانات التي تريد استيرادها من ملف Excel . يمكنك تحميل القالب لكل نوع من أجل تهيئة الأعمدة بشكل صحيح.</p>

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

                $descriptions = [
                    'universities' => 'تحميل واستيراد بيانات الجامعات (الاسم، العنوان، الهاتف، حالة التفعيل).',
                    'branches' => 'استيراد الفروع وربطها بالجامعات عبر المعرف (university_id).',
                    'colleges' => 'استيراد الكليات وربطها بالفرع المناسب.',
                    'majors' => 'استيراد التخصصات وربطها بالكلية.',
                    'med_devices' => 'استيراد الأجهزة الطبية وربطها بالمواد.',
                    'med_subjects' => 'استيراد المواد الطبية وربطها بالأجهزة إن وُجدت.',
                    'med_topics' => 'استيراد المواضيع الفرعية المرتبطة بالمواد.',
                    'med_doctors' => 'استيراد بيانات الدكاترة وربطهم بالمواد.',
                ];
            @endphp

            <div class="row g-3">
                @foreach($types as $t)
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;font-size:18px">@switch($t) @case('universities') 🎓 @break @case('branches') 🏢 @break @case('colleges') 🏫 @break @case('majors') 📚 @break @case('med_devices') ⚙️ @break @case('med_subjects') 🧾 @break @case('med_topics') 🗂️ @break @case('med_doctors') 👩‍⚕️ @break @default 📁 @endswitch</div>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-1">{{ $labels[$t] ?? ucfirst($t) }}</h5>
                                        <div class="text-muted small">{{ $descriptions[$t] ?? 'استيراد بيانات' }}</div>
                                    </div>
                                </div>

                                <div class="mt-3 mt-auto">
                                    <a href="{{ route('admin.imports.show', $t) }}" class="btn btn-primary btn-sm me-2">فتح صفحة الاستيراد</a>
                                    <a href="{{ route('admin.imports.template', $t) }}" class="btn btn-outline-secondary btn-sm">تحميل القالب</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
