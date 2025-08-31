@extends('admin.layouts.app')
@section('title','إضافة عنصر')
@section('content')
<h4 class="mb-3">إضافة عنصر</h4>
<form action="{{ route('admin.assets.store') }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf
  @include('admin.assets.form',['asset'=>null])
  <div class="mt-3">
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.assets.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
