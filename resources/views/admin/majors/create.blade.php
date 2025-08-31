@extends('admin.layouts.app')
@section('title','تخصص جديد')
@section('content')
<h4 class="mb-3">إنشاء تخصص</h4>
<form action="{{ route('admin.majors.store') }}" method="POST">
  @csrf
  @include('admin.majors.form', ['major'=>null])
  <button class="btn btn-primary">حفظ</button>
  <a href="{{ route('admin.majors.index') }}" class="btn btn-link">رجوع</a>
</form>
@endsection
