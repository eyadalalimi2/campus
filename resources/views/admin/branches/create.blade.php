@extends('admin.layouts.app')
@section('title','إضافة فرع')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">إضافة فرع</h4>
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

<form action="{{ route('admin.branches.store') }}" method="POST" class="card p-3">
  @csrf
  @include('admin.branches.form', ['branch' => null, 'universities' => $universities ?? collect()])
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
    <a href="{{ route('admin.branches.index') }}" class="btn btn-link">إلغاء</a>
  </div>
</form>
@endsection
