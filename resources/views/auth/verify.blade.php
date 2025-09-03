@extends('auth.layout')
@section('title','تفعيل البريد الإلكتروني')

@section('content')
@if (session('status'))
  <div class="alert alert-success small">{{ session('status') }}</div>
@endif

<div class="text-center mb-3">
  <h5 class="fw-bold">رجاءً فعّل بريدك الإلكتروني</h5>
  <p class="text-muted small mb-0">
    لقد أرسلنا رابط تفعيل إلى بريدك. إذا لم يصلك، يمكنك إعادة الإرسال.
  </p>
</div>

<form method="POST" action="{{ route('verification.send') }}" class="vstack gap-3">
  @csrf
  <button class="btn btn-primary w-100">
    <i class="bi bi-envelope-paper-heart"></i> إعادة إرسال رابط التفعيل
  </button>
</form>

<hr>
<div class="text-center small">
  <form action="{{ route('logout') }}" method="POST">@csrf
    <button class="btn btn-link">تغيير الحساب / تسجيل خروج</button>
  </form>
</div>
@endsection
