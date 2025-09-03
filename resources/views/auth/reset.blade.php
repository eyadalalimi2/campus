@extends('auth.layout')
@section('title','إعادة تعيين كلمة المرور')

@section('content')
@if (session('status'))
  <div class="alert alert-success small">{{ session('status') }}</div>
@endif
@if ($errors->any())
  <div class="alert alert-danger small">{{ $errors->first() }}</div>
@endif

<form method="POST" action="{{ route('password.update') }}" class="vstack gap-3">
  @csrf
  <input type="hidden" name="token" value="{{ $token }}">
  <input type="hidden" name="email" value="{{ $email }}">

  <div>
    <label class="form-label">البريد الإلكتروني</label>
    <input type="email" class="form-control" value="{{ $email }}" disabled>
  </div>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">كلمة المرور الجديدة</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">تأكيد كلمة المرور</label>
      <input type="password" name="password_confirmation" class="form-control" required>
    </div>
  </div>

  <button class="btn btn-primary w-100">تعيين كلمة المرور</button>
</form>

<hr>
<div class="text-center small">
  <a href="{{ route('login') }}">العودة لتسجيل الدخول</a>
</div>
@endsection
