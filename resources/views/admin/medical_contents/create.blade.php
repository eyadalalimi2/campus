@extends('admin.layouts.app')

@section('title', 'إضافة محتوى طبي خاص')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3">إضافة محتوى طبي خاص</h4>

    <form action="{{ route('admin.medical_contents.store') }}" method="post" enctype="multipart/form-data" class="card p-3">
        @csrf
        @include('admin.medical_contents.form', ['mode' => 'create'])
        <div class="text-end">
            <button class="btn btn-primary">حفظ</button>
            <a href="{{ route('admin.medical_contents.index') }}" class="btn btn-secondary">رجوع</a>
        </div>
    </form>
</div>
@endsection