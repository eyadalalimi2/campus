@extends('admin.layouts.app')
@section('title','تعديل جامعة')

@section('content')
<h4 class="mb-3">تعديل جامعة</h4>

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

<form action="{{ route('admin.universities.update',$university) }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf @method('PUT')
  @include('admin.universities.form', ['university'=>$university])
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.universities.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
