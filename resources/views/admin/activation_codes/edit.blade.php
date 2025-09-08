@extends('admin.layouts.app')
@section('title','تعديل كود')

@section('content')
<h4 class="mb-3">تعديل كود: {{ $code->code }}</h4>
<form action="{{ route('admin.activation_codes.update',$code) }}" method="POST" class="card p-3">
  @csrf @method('PUT')
  @include('admin.activation_codes.form', ['code'=>$code])
  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.activation_codes.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
