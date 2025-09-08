@extends('admin.layouts.app')
@section('title','كود جديد')

@section('content')
<h4 class="mb-3">إنشاء كود فردي</h4>
<form action="{{ route('admin.activation_codes.store') }}" method="POST" class="card p-3">
  @csrf
  @include('admin.activation_codes.form', ['code'=>null])
  <div class="mt-3">
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.activation_codes.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
