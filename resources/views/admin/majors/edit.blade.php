@extends('admin.layouts.app')
@section('title','تعديل تخصص')

@section('content')
<h4 class="mb-3">تعديل تخصص</h4>

{{-- عرض أخطاء التحقق --}}
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

<form action="{{ route('admin.majors.update',$major) }}" method="POST" class="card p-3">
  @csrf @method('PUT')
  @include('admin.majors.form', ['major'=>$major])
  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.majors.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
