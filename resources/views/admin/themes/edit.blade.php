@extends('admin.layouts.app')
@section('title','تعديل ثيم الجامعة')
@section('content')
<h4 class="mb-3">تعديل ثيم: {{ $university->name }}</h4>
<form action="{{ route('admin.themes.update',$university) }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf @method('PUT')
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">اسم الجامعة</label>
      <input type="text" name="name" class="form-control" value="{{ old('name',$university->name) }}" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">اللون الأساسي</label>
      <input type="color" name="primary_color" class="form-control form-control-color" value="{{ old('primary_color',$university->primary_color) }}" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">اللون الثانوي</label>
      <input type="color" name="secondary_color" class="form-control form-control-color" value="{{ old('secondary_color',$university->secondary_color) }}" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">الشعار</label>
      <input type="file" name="logo" class="form-control">
      @if($university->logo_url)
        <img src="{{ $university->logo_url }}" class="mt-2" style="height:48px">
      @endif
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.themes.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
