@extends('admin.layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>إدارة البنرات</h2>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">إضافة بانر</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>الصورة</th>
            <th>العنوان</th>
            <th>الرابط</th>
            <th>الحالة</th>
            <th>الترتيب</th>
            <th>الاجراءات</th>
        </tr>
    </thead>
    <tbody>
        @foreach($banners as $banner)
        <tr>
            <td><img src="{{ asset('storage/'.$banner->image_path) }}" width="120"></td>
            <td>{{ $banner->title }}</td>
            <td><a href="{{ $banner->target_url }}" target="_blank">{{ $banner->target_url }}</a></td>
            <td>{{ $banner->is_active ? 'فعال' : 'معطل' }}</td>
            <td>{{ $banner->sort_order }}</td>
            <td>
                <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-warning">تعديل</a>
                <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
