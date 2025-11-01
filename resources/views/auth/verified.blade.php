@extends('auth.layout')
@section('title','تم تفعيل بريدك الإلكتروني')

@section('content')
  <div class="text-center">
    <div class="mb-3">
      <div class="mx-auto mb-2" style="width:80px;height:80px;border-radius:50%;background:#eafaf7;display:flex;align-items:center;justify-content:center;border:1px solid #c7f3ea;">
        <span style="font-size:34px;color:#0ea5a4;">✓</span>
      </div>
      <h5 class="fw-bold mb-2">تم تفعيل البريد الإلكتروني بنجاح</h5>
      <p class="text-muted small mb-0">شكرًا لك، أصبح حسابك مفعّلًا الآن ويمكنك متابعة استخدام المنصة.</p>
    </div>

    <a href="{{ \App\Providers\RouteServiceProvider::HOME }}" class="btn btn-success w-100">
      الانتقال إلى لوحة التحكم
    </a>
  </div>
@endsection
