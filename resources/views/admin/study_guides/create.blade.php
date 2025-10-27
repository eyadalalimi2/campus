@extends('admin.layouts.app')
@section('title','إضافة دليل مذاكرة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">إضافة دليل مذاكرة</h4>
  <a href="{{ route('admin.study_guides.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-right-circle"></i> رجوع
  </a>
  </div>

  @include('admin.partials.flash')

  <form action="{{ route('admin.study_guides.store') }}" method="POST" class="card p-3">
    @csrf
    @include('admin.study_guides.form')
    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
      <a href="{{ route('admin.study_guides.index') }}" class="btn btn-link">إلغاء</a>
    </div>
  </form>
@endsection
