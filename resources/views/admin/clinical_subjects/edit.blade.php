@extends('admin.layouts.app')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 m-0">تعديل مادة سريرية</h1>
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
            <strong>تحديث بيانات المادة</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.clinical_subjects.update', $clinicalSubject) }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">اسم المادة <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $clinicalSubject->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="order" class="form-label">ترتيب العرض <span class="text-danger">*</span></label>
                        <input type="number" id="order" name="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $clinicalSubject->order) }}" min="0" step="1" required>
                        @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label for="image" class="form-label">صورة المادة</label>
                        @if($clinicalSubject->image)
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <img src="{{ asset('storage/' . $clinicalSubject->image) }}" class="rounded border" style="width:64px;height:64px;object-fit:cover" alt="صورة المادة الحالية">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ asset('storage/' . $clinicalSubject->image) }}" target="_blank">
                                    <i class="bi bi-box-arrow-up-right"></i>
                                    فتح الصورة
                                </a>
                            </div>
                        @endif
                        <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        <div class="form-text">اترك الحقل فارغاً إذا كنت لا ترغب في تغييره.</div>
                        @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i>
                        تحديث
                    </button>
                    <a href="{{ route('admin.clinical_subjects.index') }}" class="btn btn-light border">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
@endsection
