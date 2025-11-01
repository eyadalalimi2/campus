@extends('admin.layouts.app')
@section('title','لوحة البيانات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">لوحة البيانات</h4>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus"></i> إضافة طالب
        </a>
        <a href="{{ route('admin.doctors.create') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-person-badge"></i> دكتور جديد
        </a>
        <a href="{{ route('admin.contents.create') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-folder-plus"></i> إضافة محتوى
        </a>
        
    </div>

    
</div>

{{-- ========= --}}
{{--  KPIs     --}}
{{-- ========= --}}
<div class="row g-3">
    {{-- الجامعات --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-uni p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="muted">عدد الجامعات</div>
            <div class="value">{{ number_format($uniTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($uniActive) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($uniInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.universities.index') }}"></a>
        </div>
    </div>

    {{-- الفروع (جديد) --}}
    <div class="col-12 col-md-6 col-xl-3">
    <div class="card kpi-card grad-branch p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-diagram-3-fill"></i></div>
            <div class="muted">عدد الفروع</div>
            <div class="value">{{ number_format($branchTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($branchActive) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($branchInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.branches.index') }}"></a>
        </div>
    </div>

    {{-- الكليات --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-col p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-building-fill"></i></div>
            <div class="muted">عدد الكليات</div>
            <div class="value">{{ number_format($colTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($colActive) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($colInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.colleges.index') }}"></a>
        </div>
    </div>

    {{-- التخصصات --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-topic p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-layers-fill"></i></div>
            <div class="muted">عدد الأقسام (التخصصات)</div>
            <div class="value">{{ number_format($majTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($majActive) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($majInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.majors.index') }}"></a>
        </div>
    </div>

    {{-- الكورسات --}}
    <div class="col-12 col-md-6 col-xl-3">
    <div class="card kpi-card grad-courses p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-easel2-fill"></i></div>
            <div class="muted">عدد الكورسات</div>
            <div class="value">{{ number_format($coursesTotal ?? 0) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($coursesActive ?? 0) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($coursesInactive ?? 0) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.courses.index') }}"></a>
        </div>
    </div>

    {{-- مساعدي المحتوى --}}
    <div class="col-12 col-md-6 col-xl-3">
    <div class="card kpi-card grad-assistants p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-person-lines-fill"></i></div>
            <div class="muted">مساعدي المحتوى</div>
            <div class="value">{{ number_format($assistantsTotal ?? 0) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($assistantsActive ?? 0) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($assistantsInactive ?? 0) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.content_assistants.index') }}"></a>
        </div>
    </div>

    {{-- أكواد التفعيل --}}
    <div class="col-12 col-md-6 col-xl-3">
    <div class="card kpi-card grad-activation p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-key-fill"></i></div>
            <div class="muted">أكواد التفعيل</div>
            <div class="value">{{ number_format($activationCodesTotal ?? 0) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($activationCodesActive ?? 0) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($activationCodesInactive ?? 0) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.activation_codes.index') }}"></a>
        </div>
    </div>
    {{-- الدكاترة --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-doctor p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-person-badge-fill"></i></div>
            <div class="muted">عدد الدكاترة</div>
            <div class="value">{{ number_format($docTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($docActive) }}</span>
                <span class="badge bg-dark"> موقوف: {{ number_format($docInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.med_doctors.index') }}"></a>
        </div>
    </div>
</div>

{{-- ======= بطاقات إضافية: مواد/أجهزة/مدونة/اشتراكات + ملخص المحتوى ======= --}}
@php $contentTotal = ($cntFile ?? 0) + ($cntVideo ?? 0) + ($cntLink ?? 0); @endphp

<div class="row g-3 mt-1">
    {{-- المواد --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-mat p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-journal-bookmark-fill"></i></div>
            <div class="muted">عدد المواد</div>
            <div class="value">{{ number_format($matTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($matActive) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($matInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.med_subjects.index') }}"></a>
        </div>
    </div>

    {{-- الأجهزة --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-dev p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-cpu-fill"></i></div>
            <div class="muted">عدد الأجهزة</div>
            <div class="value">{{ number_format($devTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($devActive) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($devInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.med_devices.index') }}"></a>
        </div>
    </div>

    {{-- المدونات --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-blog p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-journal-richtext"></i></div>
            <div class="muted">عدد المدونات</div>
            <div class="value">{{ number_format($blogTotal) }}</div>
            <div class="d-flex gap-2 flex-wrap mt-2 small">
                <span class="badge bg-light text-dark">منشورة: {{ number_format($blogPublished) }}</span>
                <span class="badge bg-dark">مسودة: {{ number_format($blogDraft) }}</span>
                <span class="badge bg-secondary">مؤرشفة: {{ number_format($blogArchived) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.blogs.index') }}"></a>
        </div>
    </div>

    {{-- الاشتراكات --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-subscription p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-credit-card-fill"></i></div>
            <div class="muted">عدد الاشتراكات</div>
            <div class="value">{{ number_format($subTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">نشطة: {{ number_format($subActive) }}</span>
                <span class="badge bg-dark">أخرى: {{ number_format($subOther) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.subscriptions.index') }}"></a>
        </div>
    </div>

    {{-- المواضيع --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-topic p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-bookmarks-fill"></i></div>
            <div class="muted">عدد المواضيع</div>
            <div class="value">{{ number_format($discTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($discActive) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($discInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.med_topics.index') }}"></a>
        </div>
    </div>

    {{-- البرامج --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-major p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-play-btn-fill"></i></div>
            <div class="muted">عدد الفيديوهات</div>
            <div class="value">{{ number_format($progTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($progActive) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($progInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.med_videos.index') }}"></a>
        </div>
    </div>

    {{-- التقاويم الأكاديمية --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-calendar p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-folder2-open"></i></div>
            <div class="muted">عدد الملفات</div>
            <div class="value">{{ number_format($calTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">نشطة: {{ number_format($calActive) }}</span>
                <span class="badge bg-dark">موقوفة: {{ number_format($calInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.med_resources.index') }}"></a>
        </div>
    </div>

    {{-- عدد الطلاب  --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-student p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-calendar-event-fill"></i></div>
            <div class="muted">عدد الطلاب </div>
            <div class="value">{{ number_format($termTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">نشطة: {{ number_format($termActive) }}</span>
                <span class="badge bg-dark">موقوفة: {{ number_format($termInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.users.index') }}"></a>
        </div>
    </div>

    {{-- المحتوى --}}
    <div class="col-12">
        <div class="card kpi-card grad-cnt p-3 position-relative">
            <div class="icon-wrap"><i class="bi bi-folder2-open"></i></div>
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                <div>
                    <div class="muted">المحتوى الطبي الخاص</div>
                    <div class="value">{{ number_format($contentTotal) }}</div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark px-3 py-2">فيديو: {{ number_format($cntVideo) }}</span>
                    <span class="badge bg-dark px-3 py-2">ملفات: {{ number_format($cntFile) }}</span>
                    <span class="badge bg-white text-dark px-3 py-2 border">روابط: {{ number_format($cntLink) }}</span>
                </div>
            </div>
            <a class="stretched-link" href="{{ route('admin.medical_contents.index') }}"></a>
        </div>
    </div>
</div>

{{-- ...existing code... --}}

{{-- الرسوم البيانية --}}
<div class="row g-3 mt-1">
    <div class="col-lg-6">
        <div class="card card-soft p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">توزيع الطلاب على الجامعات (Top 10)</h6>
                <i class="bi bi-bar-chart-line"></i>
            </div>
            <div style="height: 220px">
                <canvas id="chartStudentsPerUni"></canvas>
            </div>
        </div>
    </div>

    {{-- بطاقة: متوسط تقييم التطبيق + توزيع التقييمات --}}
    <div class="col-lg-6">
        <div class="card card-soft p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">متوسط تقييم التطبيق</h6>
                <i class="bi bi-star-fill" style="color:#f59e0b"></i>
            </div>
            <div class="row align-items-stretch g-3 mt-2" style="min-height: 220px">
              <div class="col-md-5 d-flex flex-column align-items-center justify-content-center">
                <div class="display-6 fw-bold mb-2">{{ number_format($reviewsAvg ?? 0, 1) }}</div>
                <div class="fs-4 mb-2">
                    @php $avg = (float)($reviewsAvg ?? 0); @endphp
                    @for($i=1;$i<=5;$i++)
                        @if($i <= floor($avg))
                            <span class="text-warning">★</span>
                        @elseif($i - $avg < 1)
                            <span class="text-warning">☆</span>
                        @else
                            <span class="text-muted">☆</span>
                        @endif
                    @endfor
                </div>
                <div class="text-muted small">عدد التقييمات المعتمدة: {{ number_format($reviewsCountApproved ?? 0) }}</div>
                <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-primary mt-3">إدارة التقييمات</a>
              </div>
                            <div class="col-md-7">
                                <div style="height: 200px">
                                    <canvas id="chartReviewsDistribution" class="w-100 h-100" style="width:100%;height:100%"></canvas>
                                    <div id="chartReviewsFallback" class="mt-2"></div>
                                </div>
                            </div>
            </div>
        </div>
    </div>
</div>

{{-- المخططات الدائرية --}}
<div class="row g-3 mt-1">
    <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-3 p-3 h-100 pie-card">
            <h6 class="fw-bold mb-3 text-primary">
                <i class="bi bi-pie-chart-fill me-1"></i> توزيع الطلاب حسب الحالة
            </h6>
            <div class="pie-wrap">
                <canvas id="pieStudentsStatus"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-lg border-0 rounded-3 p-3 h-100 pie-card alt">
            <h6 class="fw-bold mb-3 text-danger">
                <i class="bi bi-people-fill me-1"></i> توزيع الطلاب حسب الجنس
            </h6>
            <div class="pie-wrap">
                <canvas id="pieStudentsGender"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- أحدث السجلات --}}
<div class="row g-3 mt-1">
    <div class="col-lg-6">
        <div class="card card-soft">
            <div class="card-header bg-white"><strong>أحدث الطلاب</strong></div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>الاسم</th>
                            <th>الرقم الأكاديمي</th>
                            <th>الجامعة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestStudents as $s)
                            <tr>
                                <td>{{ $s->name ?? '—' }}</td>
                                <td class="text-muted">{{ $s->student_number ?? '—' }}</td>
                                <td class="small text-muted">{{ optional($s->university)->name ?? '—' }}</td>
                                <td class="small text-muted">{{ $s->created_at?->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">—</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-soft">
            <div class="card-header bg-white"><strong>أحدث المحتوى</strong></div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>العنوان</th>
                            <th>النوع</th>
                            <th>الجامعة</th>
                            <th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestContent as $c)
                            <tr>
                                <td>{{ $c->title }}</td>
                                <td class="small">
                                    @if ($c->type === 'file')
                                        <span class="badge bg-secondary">ملف</span>
                                    @elseif($c->type === 'video')
                                        <span class="badge bg-info text-dark">فيديو</span>
                                    @else
                                        <span class="badge bg-light text-dark">رابط</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ optional($c->university)->name ?? '—' }}</td>
                                <td class="small text-muted">{{ $c->created_at?->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">—</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ملخص سريع للجامعات --}}
<div class="row g-3 mt-3">
    <div class="col-lg-6">
        <div class="card card-soft h-100">
            <div class="card-header bg-white"><strong>ملخص سريع للجامعات</strong></div>
            <div class="table-responsive">

                {{-- خرائط مجمّعة لتفادي N+1 وتصحيح الكليات (جامعات ← فروع ← كليات) --}}
                @php
                    $uStudents = \App\Models\User::selectRaw('university_id, COUNT(*) as c')
                        ->whereNotNull('university_id')
                        ->groupBy('university_id')
                        ->pluck('c','university_id');

                    $uColleges = \App\Models\College::selectRaw('university_branches.university_id as uid, COUNT(colleges.id) as c')
                        ->join('university_branches','university_branches.id','=','colleges.branch_id')
                        ->groupBy('university_branches.university_id')
                        ->pluck('c','uid');

                    $uMaterials = \App\Models\Material::selectRaw('university_id, COUNT(*) as c')
                        ->whereNotNull('university_id')
                        ->groupBy('university_id')
                        ->pluck('c','university_id');
                @endphp

                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>الجامعة</th>
                            <th>الطلاب</th>
                            <th>الكليات</th>
                            <th>المواد</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($universitiesQuick as $u)
                            <tr>
                                <td class="fw-semibold">{{ $u->name }}</td>
                                <td class="text-muted">{{ number_format($uStudents[$u->id] ?? 0) }}</td>
                                <td class="text-muted">{{ number_format($uColleges[$u->id] ?? 0) }}</td>
                                <td class="text-muted">{{ number_format($uMaterials[$u->id] ?? 0) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">—</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- أحدث المدونات --}}
    <div class="col-lg-6">
        <div class="card card-soft h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong>أحدث المدونات</strong>
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-sm btn-outline-secondary">عرض الكل</a>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>العنوان</th><th>الكاتب</th><th>الحالة</th><th>التاريخ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestBlogs as $b)
                            <tr>
                                <td class="fw-semibold">{{ $b->title }}</td>
                                <td class="text-muted">{{ $b->doctor?->name ?? 'فريق التحرير' }}</td>
                                <td>
                                    @switch($b->status)
                                        @case('published') <span class="badge bg-success">منشورة</span> @break
                                        @case('draft')     <span class="badge bg-secondary">مسودة</span> @break
                                        @case('archived')  <span class="badge bg-dark">مؤرشفة</span> @break
                                        @default           <span class="badge bg-light text-dark">{{ $b->status }}</span>
                                    @endswitch
                                </td>
                                <td class="small text-muted">{{ ($b->published_at ?? $b->created_at)?->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted">لا توجد تدوينات بعد.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@push('scripts')
{{-- تحميل Chart.js من CDN مع خطة بديلة (fallback) في حال فشل CDN الأول --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
    (function(){
        function load(src, cb){
            var s=document.createElement('script'); s.src=src; s.async=false;
            s.onload=function(){ cb && cb(true); };
            s.onerror=function(){ cb && cb(false); };
            document.head.appendChild(s);
        }
        // إن لم تُحمَّل Chart.js من الـ CDN الأول نجرّب بدائل
        function ensureChart(){
            if (window.Chart) return;
            var cdns=[
                'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js',
                'https://unpkg.com/chart.js@4.4.1/dist/chart.umd.min.js'
            ];
            var i=0;
            (function next(){
                if (window.Chart || i>=cdns.length) return;
                load(cdns[i++], function(){ if(!window.Chart) next(); });
            })();
        }
        ensureChart();
    })();
</script>

<script id="dashboard-data" type="application/json">
{!! json_encode([
    'studentsPerUniversity' => [
        'labels' => $studentsPerUniversity->pluck('uname')->map(fn($n) => $n ?: '—')->values()->all(),
        'data'   => $studentsPerUniversity->pluck('c')->values()->all(),
    ],
    // جديد: بيانات الفروع
    'studentsPerBranch' => [
        'labels' => $studentsPerBranch->pluck('ub_name')->map(fn($n) => $n ?: '—')->values()->all(),
        'data'   => $studentsPerBranch->pluck('c')->values()->all(),
    ],
    'studentsMonthly' => [
        'labels' => $studentsMonthly->pluck('ym')->values()->all(),
        'data'   => $studentsMonthly->pluck('c')->values()->all(),
    ],
    // توزيع التقييمات (1..5) الموافق عليها
    'reviewsDistribution' => [
        'labels' => ['1','2','3','4','5'],
        'data'   => [
            (int)($reviewsDistribution[1] ?? 0),
            (int)($reviewsDistribution[2] ?? 0),
            (int)($reviewsDistribution[3] ?? 0),
            (int)($reviewsDistribution[4] ?? 0),
            (int)($reviewsDistribution[5] ?? 0),
        ],
    ],
    'pieStatus' => [
        'active'    => $stdActive,
        'suspended' => $stdSuspended,
        'graduated' => $stdGrad,
    ],
    'pieGender' => [
        'male'   => $pieGender['male'] ?? 0,
        'female' => $pieGender['female'] ?? 0,
    ]
], JSON_UNESCAPED_UNICODE) !!}
</script>

<script src="{{ asset('js/dashboard.js') }}?v={{ \Illuminate\Support\Facades\File::exists(public_path('js/dashboard.js')) ? filemtime(public_path('js/dashboard.js')) : '1' }}"></script>
@endpush
