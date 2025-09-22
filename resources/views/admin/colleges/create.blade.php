@extends('admin.layouts.app')
@section('title','كلية جديدة')

@section('content')
<h4 class="mb-3">إنشاء كلية</h4>

<form action="{{ route('admin.colleges.store') }}" method="POST">
  @csrf
  @include('admin.colleges.form', ['college' => null])
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">
      <i class="bi bi-check2"></i> حفظ
    </button>
    <a href="{{ route('admin.colleges.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
