@extends('admin.layouts.app')
@section('title','تعديل كلية')

@section('content')
<h4 class="mb-3">تعديل كلية</h4>

<form action="{{ route('admin.colleges.update', $college) }}" method="POST">
  @csrf
  @method('PUT')
  @include('admin.colleges.form', ['college' => $college])
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">
      <i class="bi bi-save"></i> تحديث
    </button>
    <a href="{{ route('admin.colleges.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
