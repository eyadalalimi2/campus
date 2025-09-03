@extends('auth.layout')
@section('title','استعادة كلمة المرور')

@section('content')
@if (session('status'))
  <div class="alert alert-success small">{{ session('status') }}</div>
@endif
@if($errors->any())
  <div class="alert alert-danger small">{{ $errors->first() }}</div>
@endif
{{-- الشعار --}}
        <img src="{{ asset('storage/images/icon.png') }}" alt="شعار المناهج الأكاديمية"
             class="d-block mx-auto mb-3" style="height:150px;width:auto;">

<form method="POST" action="{{ route('password.email') }}" class="vstack gap-3">
  @csrf
  <div>
    <label class="form-label">البريد الإلكتروني</label>
    <input type="email" name="email" class="form-control" required autofocus>
  </div>
  <button class="btn btn-primary w-100">إرسال رابط الاستعادة</button>
</form>

<hr>
<div class="text-center small">
  <a href="{{ route('login') }}">العودة لتسجيل الدخول</a>
</div>
@endsection
