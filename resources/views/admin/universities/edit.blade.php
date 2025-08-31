@extends('admin.layouts.app')
@section('title','تعديل جامعة')
@section('content')
<h4 class="mb-3">تعديل جامعة</h4>
<form action="{{ route('admin.universities.update',$university) }}" method="POST" enctype="multipart/form-data">
  @csrf @method('PUT')
  @include('admin.universities.form', ['university'=>$university])
  <button class="btn btn-primary">تحديث</button>
  <a href="{{ route('admin.universities.index') }}" class="btn btn-link">رجوع</a>
</form>
@endsection
