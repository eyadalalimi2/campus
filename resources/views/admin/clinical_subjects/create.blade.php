@extends('admin.layouts.app')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 m-0">إضافة مادة سريرية</h1>
        <a href="{{ route('admin.clinical_subjects.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right"></i>
            رجوع للقائمة
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>تحقق من الحقول التالية:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <strong>بيانات المادة</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.clinical_subjects.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">اسم المادة <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="مثال: الجراحة العامة" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="order" class="form-label">ترتيب العرض <span class="text-danger">*</span></label>
                        <input type="number" id="order" name="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 0) }}" min="0" step="1" required>
                        @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="image" class="form-label">صورة المادة</label>
                        <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        <div class="form-text">اختياري - يُفضّل صورة مربعة 512x512.</div>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i>
                        حفظ
                    </button>
                    <a href="{{ route('admin.clinical_subjects.index') }}" class="btn btn-light border">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
@endsection
