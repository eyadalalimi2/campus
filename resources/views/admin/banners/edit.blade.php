@extends('admin.layouts.app')

@section('content')
<h2>تعديل بانر</h2>

<form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>العنوان</label>
        <input type="text" name="title" value="{{ $banner->title }}" class="form-control">
    </div>
    <div class="mb-3">
        <label>الصورة الحالية</label><br>
        <img src="{{ asset('storage/'.$banner->image_path) }}" width="150">
    </div>
    <div class="mb-3">
        <label>تغيير الصورة</label>
        <input type="file" name="image" class="form-control">
    </div>
    <div class="mb-3">
        <label>الرابط</label>
        <input type="url" name="target_url" value="{{ $banner->target_url }}" class="form-control">
    </div>
    <div class="mb-3">
        <label>فتح في نافذة جديدة</label>
        <input type="checkbox" name="open_external" value="1" {{ $banner->open_external ? 'checked' : '' }}>
    </div>
    <div class="mb-3">
        <label>الترتيب</label>
        <input type="number" name="sort_order" value="{{ $banner->sort_order }}" class="form-control">
    </div>
    <div class="mb-3">
        <label>الحالة</label>
        <select name="is_active" class="form-control">
            <option value="1" {{ $banner->is_active ? 'selected' : '' }}>فعال</option>
            <option value="0" {{ !$banner->is_active ? 'selected' : '' }}>معطل</option>
        </select>
    </div>
    <button class="btn btn-success">تحديث</button>
</form>
@endsection
