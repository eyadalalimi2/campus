@extends('admin.layouts.app')

@section('title', 'تعديل محتوى')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">تعديل المحتوى #{{ $content->id }}</h3>
        <a href="{{ route('admin.app_contents.index') }}" class="btn btn-light"><i class="fa fa-arrow-right"></i> رجوع</a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.app_contents.update', $content) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label">العنوان <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $content->title) }}" class="form-control @error('title') is-invalid @enderror" required>
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">الوصف</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $content->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">الرابط (اختياري)</label>
                        <input type="url" name="link_url" value="{{ old('link_url', $content->link_url) }}" class="form-control @error('link_url') is-invalid @enderror">
                        @error('link_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الترتيب</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $content->sort_order) }}" class="form-control @error('sort_order') is-invalid @enderror">
                        @error('sort_order') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الحالة</label>
                        <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                            <option value="1" {{ old('is_active', (int)$content->is_active)==1 ? 'selected' : '' }}>مفعل</option>
                            <option value="0" {{ old('is_active', (int)$content->is_active)==0 ? 'selected' : '' }}>مخفي</option>
                        </select>
                        @error('is_active') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label d-block">الصورة الحالية</label>
                        @if($content->image_url)
                            <img src="{{ $content->image_url }}" style="width:120px;height:120px;object-fit:cover;border-radius:8px;border:1px solid #eee;">
                        @else
                            <span class="text-muted">لا توجد صورة</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">استبدال الصورة (اختياري)</label>
                        <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_image" id="removeImage" value="1">
                            <label for="removeImage" class="form-check-label">إزالة الصورة الحالية</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="btn btn-primary"><i class="fa fa-save"></i> حفظ التعديلات</button>
                    <a href="{{ route('admin.app_contents.index') }}" class="btn btn-light">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
