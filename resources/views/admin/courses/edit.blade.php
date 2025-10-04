@extends('admin.layouts.app')

@section('title','تعديل كورس')

@section('content')
<div class="container-fluid">
    <h4>تعديل الكورس</h4>

    <form action="{{ route('admin.courses.update',$course->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">عنوان الكورس</label>
            <input type="text" name="title" class="form-control" value="{{ $course->title }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">الترتيب</label>
            <input type="number" name="sort_order" class="form-control" value="{{ $course->sort_order }}">
        </div>

        <div class="mb-3">
            <label class="form-label">الحالة</label>
            <select name="is_active" class="form-select">
                <option value="1" {{ $course->is_active ? 'selected' : '' }}>مفعل</option>
                <option value="0" {{ !$course->is_active ? 'selected' : '' }}>معطل</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">تحديث</button>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
