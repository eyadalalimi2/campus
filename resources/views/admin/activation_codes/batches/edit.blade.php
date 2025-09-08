@extends('admin.layouts.app')
@section('title','تعديل دفعة')

@section('content')
<h4 class="mb-3">تعديل دفعة: {{ $batch->name }}</h4>

<form action="{{ route('admin.activation_code_batches.update', $batch) }}" method="POST" class="card p-3">
  @csrf @method('PUT')
  @include('admin.activation_codes.batches.form', ['batch' => $batch])

  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.activation_code_batches.show', $batch) }}" class="btn btn-outline-secondary">عرض</a>
    <a href="{{ route('admin.activation_code_batches.export', $batch) }}" class="btn btn-outline-success">تصدير</a>
    <a href="{{ route('admin.activation_code_batches.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
