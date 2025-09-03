@extends('admin.layouts.app')
@section('title','تعديل تدوينة')
@section('content')
<h4 class="mb-3">تعديل تدوينة: {{ $blog->title }}</h4>
<form action="{{ route('admin.blogs.update',$blog) }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf @method('PUT')
  @include('admin.blogs.form', ['blog'=>$blog])
  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
