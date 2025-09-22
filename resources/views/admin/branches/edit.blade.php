@extends('admin.layouts.app')
@section('title','تعديل فرع')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">تعديل فرع: {{ $branch->name }}</h4>
  <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-right-circle"></i> رجوع
  </a>
</div>

@if ($errors->any())
  <div class="alert alert-danger">
    <div class="fw-bold mb-1">حدثت أخطاء أثناء الحفظ:</div>
    <ul class="mb-0">
      @foreach ($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('admin.branches.update', $branch) }}" method="POST" class="card p-3">
  @csrf @method('PUT')
  @include('admin.branches.form', ['branch' => $branch, 'universities' => $universities ?? collect()])
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary"><i class="bi bi-save"></i> تحديث</button>
    <a href="{{ route('admin.branches.index') }}" class="btn btn-link">إلغاء</a>
  </div>
</form>
@endsection
