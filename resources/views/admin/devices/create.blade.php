@extends('admin.layouts.app')
@section('title','إضافة جهاز')
@section('content')
<h4 class="mb-3">إضافة جهاز</h4>
<form action="{{ route('admin.devices.store') }}" method="POST" class="card p-3">
  @csrf
  @include('admin.devices.form',['device'=>null])
  <div class="mt-3">
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.devices.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
