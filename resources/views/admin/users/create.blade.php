@extends('admin.layouts.app')
@section('title','إضافة طالب')
@section('content')
<h4 class="mb-3">إضافة طالب</h4>
<form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf
  @include('admin.users.form')
  <div class="mt-3">
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
