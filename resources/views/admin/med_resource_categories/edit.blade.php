@extends('admin.layouts.app')
@section('title','تعديل تصنيف')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">تعديل تصنيف</h1>
  <a href="{{ route('admin.med_resource-categories.index') }}" class="btn btn-light"><i class="bi bi-arrow-right"></i> رجوع</a>
</div>

<form action="{{ route('admin.med_resource-categories.update',$resource_category) }}" method="POST" class="card p-3">
  @csrf @method('PUT')
  @include('admin.med_resource_categories.form',['category'=>$resource_category])
  <div class="mt-3"><button class="btn btn-primary"><i class="bi bi-save2"></i> تحديث</button></div>
</form>
@endsection
