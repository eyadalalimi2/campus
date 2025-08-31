@extends('admin.layouts.app')
@section('title','تعديل جهاز/مهمة')
@section('content')
<h4 class="mb-3">تعديل: {{ $device->name }}</h4>
<form action="{{ route('admin.devices.update',$device) }}" method="POST" class="card p-3">
  @csrf @method('PUT')
  @include('admin.devices.form',['device'=>$device])
  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.devices.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
