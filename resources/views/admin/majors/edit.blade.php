@extends('admin.layouts.app')
@section('title','تعديل تخصص')
@section('content')
<h4 class="mb-3">تعديل تخصص</h4>
<form action="{{ route('admin.majors.update',$major) }}" method="POST">
  @csrf @method('PUT')
  @include('admin.majors.form', ['major'=>$major])
  <button class="btn btn-primary">تحديث</button>
  <a href="{{ route('admin.majors.index') }}" class="btn btn-link">رجوع</a>
</form>
@endsection
