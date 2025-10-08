@extends('admin.layouts.app')

@section('title', 'تعديل الميزة')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">تعديل الميزة #{{ $feature->id }}</h3>
        <a href="{{ route('admin.app_features.index') }}" class="btn btn-light">
            <i class="fa fa-arrow-right"></i> رجوع
        </a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.app_features.update', $feature) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">نص الميزة <span class="text-danger">*</span></label>
                    <textarea name="text" rows="4" class="form-control @error('text') is-invalid @enderror" required>{{ old('text', $feature->text) }}</textarea>
                    @error('text') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">الترتيب</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $feature->sort_order) }}" class="form-control @error('sort_order') is-invalid @enderror">
                        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">الحالة</label>
                        <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                            <option value="1" {{ old('is_active', (int)$feature->is_active)==1 ? 'selected' : '' }}>مفعل</option>
                            <option value="0" {{ old('is_active', (int)$feature->is_active)==0 ? 'selected' : '' }}>مخفي</option>
                        </select>
                        @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-block">الصورة الحالية</label>
                        @if($feature->image_url)
                            <img src="{{ $feature->image_url }}" alt="image" style="width:96px;height:96px;object-fit:cover;border-radius:8px;border:1px solid #eee;">
                        @else
                            <span class="text-muted">لا توجد صورة</span>
                        @endif
                    </div>
                </div>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label">استبدال الصورة (اختياري)</label>
                        <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="removeImage" name="remove_image">
                            <label class="form-check-label" for="removeImage">
                                إزالة الصورة الحالية
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-primary">
                        <i class="fa fa-save"></i> حفظ التعديلات
                    </button>
                    <a href="{{ route('admin.app_features.index') }}" class="btn btn-light">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
