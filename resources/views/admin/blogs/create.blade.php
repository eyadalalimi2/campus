@extends('admin.layouts.app')
@section('title','تدوينة جديدة')
@section('content')
<h4 class="mb-3">تدوينة جديدة</h4>
<form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf
  @include('admin.blogs.form', ['blog'=>null])
  <div class="mt-3">
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
