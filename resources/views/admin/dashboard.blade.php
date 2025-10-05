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
            <div class="icon-wrap"><i class="bi bi-building-fill"></i></div>
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
        <div class="card kpi-card grad-col p-3 h-100 position-relative">
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
            <div class="icon-wrap"><i class="bi bi-bank2"></i></div>
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
        <div class="card kpi-card grad-maj p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-diagram-3-fill"></i></div>
            <div class="muted">عدد الأقسام (التخصصات)</div>
            <div class="value">{{ number_format($majTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($majActive) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($majInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.majors.index') }}"></a>
        </div>
    </div>

    {{-- الدكاترة --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-doc p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-person-badge-fill"></i></div>
            <div class="muted">عدد الدكاترة</div>
            <div class="value">{{ number_format($docTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">جامعي: {{ number_format($docUni) }}</span>
                <span class="badge bg-dark">مستقل: {{ number_format($docInd) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.doctors.index') }}"></a>
        </div>
    </div>
</div>

{{-- ======= بطاقات إضافية: مواد/أجهزة/مدونة/اشتراكات + ملخص المحتوى ======= --}}
@php $contentTotal = ($cntFile ?? 0) + ($cntVideo ?? 0) + ($cntLink ?? 0); @endphp

<div class="row g-3 mt-1">
    {{-- المواد --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-mat p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-journal-text"></i></div>
            <div class="muted">عدد المواد</div>
            <div class="value">{{ number_format($matTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($matActive) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($matInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.materials.index') }}"></a>
        </div>
    </div>

    {{-- الأجهزة --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-dev p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-cpu"></i></div>
            <div class="muted">عدد الأجهزة/المهام</div>
            <div class="value">{{ number_format($devTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($devActive) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($devInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.devices.index') }}"></a>
        </div>
    </div>

    {{-- المدونات --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-blog p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-newspaper"></i></div>
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
        <div class="card kpi-card grad-sub p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-credit-card-2-front-fill"></i></div>
            <div class="muted">عدد الاشتراكات</div>
            <div class="value">{{ number_format($subTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">نشطة: {{ number_format($subActive) }}</span>
                <span class="badge bg-dark">أخرى: {{ number_format($subOther) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.subscriptions.index') }}"></a>
        </div>
    </div>

    {{-- المجالات --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-sub p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-bookmark-fill"></i></div>
            <div class="muted">عدد المجالات</div>
            <div class="value">{{ number_format($discTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($discActive) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($discInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.disciplines.index') }}"></a>
        </div>
    </div>

    {{-- البرامج --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-maj p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-collection-fill"></i></div>
            <div class="muted">عدد البرامج</div>
            <div class="value">{{ number_format($progTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">مفعل: {{ number_format($progActive) }}</span>
                <span class="badge bg-dark">موقوف: {{ number_format($progInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.programs.index') }}"></a>
        </div>
    </div>

    {{-- التقاويم الأكاديمية --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-doc p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-calendar3"></i></div>
            <div class="muted">التقاويم الأكاديمية</div>
            <div class="value">{{ number_format($calTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">نشطة: {{ number_format($calActive) }}</span>
                <span class="badge bg-dark">موقوفة: {{ number_format($calInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.academic-calendars.index') }}"></a>
        </div>
    </div>

    {{-- الفصول الأكاديمية --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card kpi-card grad-col p-3 h-100 position-relative">
            <div class="icon-wrap"><i class="bi bi-calendar-event-fill"></i></div>
            <div class="muted">الفصول الأكاديمية</div>
            <div class="value">{{ number_format($termTotal) }}</div>
            <div class="d-flex gap-3 mt-2 small">
                <span class="badge bg-light text-dark">نشطة: {{ number_format($termActive) }}</span>
                <span class="badge bg-dark">موقوفة: {{ number_format($termInactive) }}</span>
            </div>
            <a class="stretched-link" href="{{ route('admin.academic-terms.index') }}"></a>
        </div>
    </div>

    {{-- المحتوى --}}
    <div class="col-12">
        <div class="card kpi-card grad-cnt p-3 position-relative">
            <div class="icon-wrap"><i class="bi bi-folder2-open"></i></div>
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                <div>
                    <div class="muted">المحتوى التعليمي</div>
                    <div class="value">{{ number_format($contentTotal) }}</div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark px-3 py-2">فيديو: {{ number_format($cntVideo) }}</span>
                    <span class="badge bg-dark px-3 py-2">ملفات: {{ number_format($cntFile) }}</span>
                    <span class="badge bg-white text-dark px-3 py-2 border">روابط: {{ number_format($cntLink) }}</span>
                </div>
            </div>
            <a class="stretched-link" href="{{ route('admin.contents.index') }}"></a>
        </div>
    </div>
</div>

{{-- التنبيهات --}}
<div class="row g-3 mt-1">
    <div class="col-12">
        <div class="card card-soft">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <strong>الإشعارات والتنبيهات</strong>
                <span class="small text-muted">فحص سلامة البيانات</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    {{-- جامعات غير مفعّلة --}}
                    <div class="col-md-4">
                        <div class="alert alert-warning mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div><i class="bi bi-exclamation-triangle-fill"></i> جامعات غير مفعلة</div>
                                <span class="badge bg-dark">{{ $inactiveUniCount }}</span>
                            </div>
                            @if ($inactiveUniCount > 0)
                                <ul class="mt-2 mb-0 small">
                                    @foreach ($inactiveUniversities as $n)
                                        <li>{{ $n }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="small text-muted mt-2">لا يوجد.</div>
                            @endif
                        </div>
                    </div>

                    {{-- مواد بلا محتوى --}}
                    <div class="col-md-4">
                        <div class="alert alert-info mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div><i class="bi bi-folder-x"></i> مواد بلا محتوى</div>
                                <span class="badge bg-dark">{{ $matNoContentCount }}</span>
                            </div>
                            @if ($matNoContentCount > 0)
                                <ul class="mt-2 mb-0 small">
                                    @foreach ($materialsWithoutContent as $n)
                                        <li>{{ $n }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="small text-muted mt-2">لا يوجد.</div>
                            @endif
                        </div>
                    </div>

                    {{-- أقسام بلا دكاترة --}}
                    <div class="col-md-4">
                        <div class="alert alert-secondary mb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div><i class="bi bi-diagram-3"></i> أقسام بلا دكاترة</div>
                                <span class="badge bg-dark">{{ $majNoDoctorsCount }}</span>
                            </div>
                            @if ($majNoDoctorsCount > 0)
                                <ul class="mt-2 mb-0 small">
                                    @foreach ($majorsWithoutDoctors as $n)
                                        <li>{{ $n }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="small text-muted mt-2">لا يوجد.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

    {{-- مخطط الفروع (جديد) --}}
    <div class="col-lg-6">
        <div class="card card-soft p-3 h-100">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">توزيع الطلاب على الفروع (Top 10)</h6>
                <i class="bi bi-bar-chart"></i>
            </div>
            <div style="height: 220px">
                <canvas id="chartStudentsPerBranch"></canvas>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script id="dashboard-data" type="application/json">
{!! json_encode([
    'studentsPerUniversity' => [
        'labels' => $studentsPerUniversity->pluck('uname')->map(fn($n) => $n ?: '—'),
        'data'   => $studentsPerUniversity->pluck('c'),
    ],
    // جديد: بيانات الفروع
    'studentsPerBranch' => [
        'labels' => $studentsPerBranch->pluck('ub_name')->map(fn($n) => $n ?: '—'),
        'data'   => $studentsPerBranch->pluck('c'),
    ],
    'studentsMonthly' => [
        'labels' => $studentsMonthly->pluck('ym'),
        'data'   => $studentsMonthly->pluck('c'),
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

<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
