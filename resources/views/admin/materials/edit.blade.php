@extends('admin.layouts.app')
@section('title','تعديل مادة')
@section('content')
<h4 class="mb-3">تعديل مادة: {{ $material->name }}</h4>
<form action="{{ route('admin.materials.update',$material) }}" method="POST" class="card p-3">
  @csrf @method('PUT')
  @include('admin.materials.form',['material'=>$material])
  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.materials.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
