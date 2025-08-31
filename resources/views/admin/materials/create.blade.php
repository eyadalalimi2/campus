@extends('admin.layouts.app')
@section('title','مادة جديدة')
@section('content')
<h4 class="mb-3">إنشاء مادة</h4>
<form action="{{ route('admin.materials.store') }}" method="POST" class="card p-3">
  @csrf
  @include('admin.materials.form',['material'=>null])
  <div class="mt-3">
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.materials.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
