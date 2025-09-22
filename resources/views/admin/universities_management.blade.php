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
        </div>
    </div>
</div>
@endsection
