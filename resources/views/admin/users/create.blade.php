@extends('admin.layouts.app')
@section('title','إضافة طالب')

@section('content')
<h4 class="mb-3">إضافة طالب</h4>

{{-- عرض أخطاء التحقق (اختياري لكنه مفيد) --}}
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

<form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf

  {{-- الجزئية الموحّدة للنموذج --}}
  @include('admin.users.form', [
    'user' => null,
    // تمرير المتغيّرات صراحة (اختياري إذا كانت متوفرة ضمن الـ view)
    'universities' => $universities,
    'colleges'     => $colleges,
    'majors'       => $majors,
    'countries'    => $countries,
  ])

  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
