@extends('admin.layouts.app')

@section('content')
<h2>إضافة بانر</h2>

<form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>العنوان</label>
        <input type="text" name="title" class="form-control">
    </div>
    <div class="mb-3">
        <label>الصورة</label>
        <input type="file" name="image" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>الرابط</label>
        <input type="url" name="target_url" class="form-control">
    </div>
    <div class="mb-3">
        <label>فتح في نافذة جديدة</label>
        <input type="checkbox" name="open_external" value="1" checked>
    </div>
    <div class="mb-3">
        <label>الترتيب</label>
        <input type="number" name="sort_order" value="0" class="form-control">
    </div>
    <div class="mb-3">
        <label>الحالة</label>
        <select name="is_active" class="form-control">
            <option value="1">فعال</option>
            <option value="0">معطل</option>
        </select>
    </div>
    <button class="btn btn-success">حفظ</button>
</form>
@endsection
