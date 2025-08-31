@extends('admin.layouts.app')
@section('title','تعديل عنصر')
@section('content')
<h4 class="mb-3">تعديل عنصر: {{ $asset->title }}</h4>
<form action="{{ route('admin.assets.update',$asset) }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf @method('PUT')
  @include('admin.assets.form',['asset'=>$asset])
  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.assets.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
