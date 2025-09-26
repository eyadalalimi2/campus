@extends('admin.layouts.app')
@section('title','تصنيف جديد')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">تصنيف جديد</h1>
  <a href="{{ route('admin.med_resource-categories.index') }}" class="btn btn-light"><i class="bi bi-arrow-right"></i> رجوع</a>
</div>

<form action="{{ route('admin.med_resource-categories.store') }}" method="POST" class="card p-3">
  @csrf
  @include('admin.med_resource_categories.form',['category'=>null])
  <div class="mt-3"><button class="btn btn-success"><i class="bi bi-check2-circle"></i> حفظ</button></div>
</form>
@endsection
