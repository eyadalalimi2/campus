@extends('admin.layouts.app')
@section('title', 'إدارة الثيمات')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">إدارة الثيمات</h4>
    </div>

    <div class="row g-3">
        @forelse($universities as $u)
            @php
                // تحديد رابط الشعار:
                // 1) إن وُجد logo_url في السجل نستخدمه كما هو.
                // 2) إن وُجد مسار ملف logo_path داخل التخزين العام نستخدم Storage::url().
                // 3) وإلا نستخدم صورة افتراضية (ضعها عند public/images/logo.png أو بدّل المسار).
                $logoSrc =
                    $u->logo_url ?:
                    ($u->logo_path ?? null
                        ? \Illuminate\Support\Facades\Storage::url($u->logo_path)
                        : asset('images/logo.png'));
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <img src="{{ $logoSrc }}" alt="Logo" style="height:40px;width:auto;object-fit:contain">
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $u->name }}</div>
                            <div class="small text-muted">{{ $u->address }}</div>
                        </div>
                        <a href="{{ route('admin.themes.edit', $u) }}" class="btn btn-outline-primary btn-sm">تعديل الثيم</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-muted text-center">لا توجد جامعات.</div>
        @endforelse
    </div>
@endsection
