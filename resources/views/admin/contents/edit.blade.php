@extends('admin.layouts.app')
@section('title','تعديل محتوى')
@section('content')
<h4 class="mb-3">تعديل محتوى: {{ $content->title }}</h4>
<form action="{{ route('admin.contents.update',$content) }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf @method('PUT')
  @include('admin.contents.form', ['content'=>$content])
  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.contents.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
