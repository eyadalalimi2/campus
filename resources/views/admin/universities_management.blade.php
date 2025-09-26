@extends('admin.layouts.app')

@section('title', 'إدارة الجامعات والكليات')

@section('content')
<div class="card mb-4">
    <div class="card-header fw-bold">
        إدارة الجامعات والكليات
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-4">
                <a href="{{ route('admin.universities.index') }}" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-building"></i> إدارة الجامعات
                </a>
                <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-diagram-3"></i> إدارة الفروع
                </a>
                <a href="{{ route('admin.colleges.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-bank"></i> إدارة الكليات
                </a>
                <a href="{{ route('admin.majors.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-mortarboard"></i> إدارة التخصصات
                </a>
                <a href="{{ route('admin.disciplines.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-tags"></i> إدارة المجالات
                </a>
                <a href="{{ route('admin.programs.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-journal-code"></i> إدارة البرامج
                </a>
                <a href="{{ route('admin.academic-calendars.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-calendar-week"></i> التقويم الأكاديمي
                </a>
                <a href="{{ route('admin.academic-terms.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-calendar2-range"></i> الفصول الأكاديمية
                </a>
                <a href="{{ route('admin.major_program.index') }}" class="btn btn-outline-primary w-100 mb-2">
                    <i class="bi bi-link"></i> ربط التخصص بالبرنامج
                </a>
            </div>
            <div class="col-md-4">
                <div class="nav-item mb-2">
                    <a href="{{ route('admin.countries.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="bi bi-globe2"></i> إدارة الدول
                    </a>
                </div>
                <div class="nav-item mb-2">
                    <a href="{{ route('admin.materials.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="bi bi-journal-text"></i> إدارة المواد
                    </a>
                </div>
                <div class="nav-item mb-2">
                    <a href="{{ route('admin.devices.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="bi bi-cpu"></i> إدارة الأجهزة
                    </a>
                </div>
                <div class="nav-item mb-2">
                    <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="bi bi-card-checklist"></i> الخطط
                    </a>
                </div>
                <div class="nav-item mb-2">
                    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="bi bi-credit-card-2-front"></i> الاشتراكات
                    </a>
                </div>
                <div class="nav-item mb-2">
                    <a href="{{ route('admin.activation_code_batches.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="bi bi-boxes"></i> دفعات الأكواد
                    </a>
                </div>
                <div class="nav-item mb-2">
                    <a href="{{ route('admin.activation_codes.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                        <i class="bi bi-qr-code"></i> الأكواد الفردية
                    </a>
                </div>
            </div>
                <div class="col-md-4">
                        <a href="{{ route('admin.public-colleges.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="bi bi-bank2"></i> الكليات العامة
                        </a>
                        <a href="{{ route('admin.public-majors.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="bi bi-diagram-3-fill"></i> التخصصات العامة
                        </a>
                        <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="bi bi-person-badge"></i> إدارة الدكاترة
                        </a>
                        <a href="{{ route('admin.assets.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="bi bi-collection"></i> إدارة المحتوى العام
                        </a>
                        <a href="{{ route('admin.contents.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="bi bi-folder"></i> إدارة المحتوى الخاص
                        </a>
                </div>
        </div>
    </div>
</div>
@endsection
