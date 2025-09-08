@extends('admin.layouts.app')
@section('title','تعديل طالب')
@section('content')
<h4 class="mb-3">تعديل طالب: {{ $user->name }}</h4>

{{-- عرض أخطاء التحقق (اختياري لكن مفيد) --}}
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

<form action="{{ route('admin.users.update',$user) }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf
  @method('PUT')

  {{-- الجزئية الموحّدة للنموذج (تستخدم country_id + باقي الحقول) --}}
  @include('admin.users.form', ['user'=>$user])

  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.users.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
