@extends('admin.layouts.app')
@section('title','إضافة دكتور')

@section('content')
<h4 class="mb-3">إضافة دكتور</h4>

{{-- عرض أخطاء التحقق (اختياري) --}}
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

<form action="{{ route('admin.doctors.store') }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf
  @include('admin.doctors.form', ['doctor'=>null,'selectedMajors'=>[]])
  <div class="mt-3">
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.doctors.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
