@extends('admin.layouts.app')

@section('title', 'تعديل محتوى طبي خاص')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">تعديل محتوى طبي خاص</h4>

    <form action="{{ route('admin.medical_contents.update', $medical_content->id) }}" method="post" enctype="multipart/form-data" class="card p-3">
        @csrf @method('put')
        @include('admin.medical_contents.form', ['mode' => 'edit', 'row' => $medical_content])
        <div class="text-end">
            <button class="btn btn-primary">تحديث</button>
            <a href="{{ route('admin.medical_contents.index') }}" class="btn btn-secondary">رجوع</a>
        </div>
    </form>
</div>
@endsection