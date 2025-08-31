@extends('admin.layouts.app')
@section('title','إضافة محتوى')
@section('content')
<h4 class="mb-3">إضافة محتوى</h4>
<form action="{{ route('admin.contents.store') }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf
  @include('admin.contents.form', ['content'=>null])
  <div class="mt-3">
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.contents.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
