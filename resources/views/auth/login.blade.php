@extends('auth.layout')
@section('title','تسجيل الدخول')

@section('content')
@if (session('status'))
  <div class="alert alert-success small">{{ session('status') }}</div>
@endif
@if ($errors->any())
  <div class="alert alert-danger small">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('login.post') }}" class="vstack gap-3">
  @csrf
  <div>
    <label class="form-label">البريد الإلكتروني</label>
    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
  </div>
  <div>
    <label class="form-label">كلمة المرور</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="remember" id="remember">
    <label class="form-check-label" for="remember">تذكرني</label>
  </div>
  <button class="btn btn-primary w-100">دخول</button>
</form>

<hr>
<div class="d-flex justify-content-between small">
  <a href="{{ route('register') }}">حساب جديد</a>
  <a href="{{ route('password.request') }}">نسيت كلمة المرور؟</a>
</div>
@endsection
