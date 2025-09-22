@extends('admin.layouts.app')
@section('title','جامعة جديدة')

@section('content')
<h4 class="mb-3">إنشاء جامعة</h4>

{{-- أخطاء التحقق --}}
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

<form action="{{ route('admin.universities.store') }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf
  @include('admin.universities.form', ['university'=>null])
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.universities.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
