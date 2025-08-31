@extends('admin.layouts.app')
@section('title','تعديل كلية')
@section('content')
<h4 class="mb-3">تعديل كلية</h4>
<form action="{{ route('admin.colleges.update',$college) }}" method="POST">
  @csrf @method('PUT')
  @include('admin.colleges.form', ['college'=>$college])
  <button class="btn btn-primary">تحديث</button>
  <a href="{{ route('admin.colleges.index') }}" class="btn btn-link">رجوع</a>
</form>
@endsection
