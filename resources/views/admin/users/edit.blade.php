@extends('admin.layouts.app')
@section('title','تعديل طالب')
@section('content')
<h4 class="mb-3">تعديل طالب: {{ $user->name }}</h4>
<form action="{{ route('admin.users.update',$user) }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf @method('PUT')
  @include('admin.users.form', ['user'=>$user])
  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
