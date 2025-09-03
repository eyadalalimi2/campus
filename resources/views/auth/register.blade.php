@extends('auth.layout')
@section('title','إنشاء حساب')

@section('content')
@if ($errors->any())
  <div class="alert alert-danger small">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('register.post') }}" class="vstack gap-3">
  @csrf
  <div>
    <label class="form-label">الاسم الكامل</label>
    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
  </div>
  <div>
    <label class="form-label">البريد الإلكتروني</label>
    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
  </div>
  <div>
    <label class="form-label">رقم الهاتف (اختياري)</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
  </div>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">كلمة المرور</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">تأكيد كلمة المرور</label>
      <input type="password" name="password_confirmation" class="form-control" required>
    </div>
  </div>
  <button class="btn btn-primary w-100">إنشاء الحساب</button>
</form>

<hr>
<div class="text-center small">
  <a href="{{ route('login') }}">لديك حساب؟ تسجيل الدخول</a>
</div>
@endsection
