@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-3">استيراد المحتوى عبر Excel</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <p>استيراد محتويات مثل الفيديوهات والملفات الخاصة بالمواد. اختر نوع المحتوى ثم حمّل الملف أو القالب.</p>

            @php
                $labels = [
                    'med_videos' => 'محتوى الفيديوهات',
                    'med_resources' => 'محتوى الموارد (ملفات)',
                    'clinical_subject_pdfs' => ' محتوى المواد السريرية PDF',
                    'medical_contents' => 'محتوى المواد الطبية',
                ];

                $descriptions = [
                    'med_videos' => 'استيراد فيديوهات (YouTube) وربطها بالدكاترة/المواد/المواضيع.',
                    'med_resources' => 'استيراد ملفات وموارد (PDF، ملفات) مع معلومات الصفحات والحجم.',
                    'clinical_subject_pdfs' => 'استيراد ملفات PDF مرتبطة بالمواد السريرية.',
                ];
            @endphp

            <div class="row g-3">
                @foreach($types as $t)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-start">
                                    <div class="me-3"><span style="font-size:20px;line-height:1">@switch($t) @case('med_videos') ▶️ @break @case('med_resources') 📁 @break @case('clinical_subject_pdfs') 📄 @break @default 📦 @endswitch</span></div>
                                    <div>
                                        <h5 class="card-title mb-1">{{ $labels[$t] ?? ucfirst($t) }}</h5>
                                        <div class="text-muted small">{{ $descriptions[$t] ?? 'استيراد بيانات' }}</div>
                                    </div>
                                </div>

                                <div class="mt-3 mt-auto">
                                    <a href="{{ route('admin.content_imports.show', $t) }}" class="btn btn-primary btn-sm me-2">فتح صفحة الاستيراد</a>
                                    <a href="{{ route('admin.content_imports.template', $t) }}" class="btn btn-outline-secondary btn-sm">تحميل القالب</a>
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
