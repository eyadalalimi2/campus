@extends('admin.layouts.app')

@section('title', 'إضافة محتوى')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">إضافة محتوى</h3>
        <a href="{{ route('admin.app_contents.index') }}" class="btn btn-light"><i class="fa fa-arrow-right"></i> رجوع</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.app_contents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">العنوان <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" class="form-control @error('title') is-invalid @enderror" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">الرابط (اختياري)</label>
                        <input type="url" name="link_url" value="{{ old('link_url') }}" class="form-control @error('link_url') is-invalid @enderror">
                        @error('link_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الترتيب</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control @error('sort_order') is-invalid @enderror">
                        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الحالة</label>
                        <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                            <option value="1" {{ old('is_active', 1)==1 ? 'selected' : '' }}>مفعل</option>
                            <option value="0" {{ old('is_active', 1)==0 ? 'selected' : '' }}>مخفي</option>
                        </select>
                        @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">الصورة (اختياري)</label>
                    <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4">
                    <button class="btn btn-primary"><i class="fa fa-save"></i> حفظ</button>
                    <a href="{{ route('admin.app_contents.index') }}" class="btn btn-light">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
