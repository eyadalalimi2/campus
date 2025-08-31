@extends('admin.layouts.app')
@section('title','جامعة جديدة')
@section('content')
<h4 class="mb-3">إنشاء جامعة</h4>
<form action="{{ route('admin.universities.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  @include('admin.universities.form', ['university'=>null])
  <button class="btn btn-primary">حفظ</button>
  <a href="{{ route('admin.universities.index') }}" class="btn btn-link">رجوع</a>
</form>
@endsection
