@extends('admin.layouts.app')

@section('title','إضافة كورس')

@section('content')
<div class="container-fluid">
    <h4>إضافة كورس جديد</h4>

    <form action="{{ route('admin.courses.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">عنوان الكورس</label>
            <input type="text" name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">الترتيب</label>
            <input type="number" name="sort_order" class="form-control" value="0">
        </div>

        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
