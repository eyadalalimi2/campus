@extends('auth.layout')
@section('title','رابط التفعيل غير صالح')

@section('content')
  <div class="text-center">
    <div class="mb-3">
      <div class="mx-auto mb-2" style="width:80px;height:80px;border-radius:50%;background:#fff4f4;display:flex;align-items:center;justify-content:center;border:1px solid #f8d7da;">
        <span style="font-size:34px;color:#dc3545;">!</span>
      </div>
      <h5 class="fw-bold mb-2">الرابط غير صالح أو تم استخدامه مسبقًا</h5>
      <p class="text-muted small mb-0">قد يكون الرابط منتهي الصلاحية أو تم فتحه سابقًا. لا تقلق، يمكنك طلب رابط جديد بسهولة.</p>
    </div>

    @auth
      <form method="POST" action="{{ route('verification.send') }}" class="vstack gap-3">
        @csrf
        <button class="btn btn-primary w-100">إرسال رابط تفعيل جديد</button>
      </form>
      <a href="{{ \App\Providers\RouteServiceProvider::HOME }}" class="btn btn-outline-secondary w-100 mt-2">الذهاب إلى لوحة التحكم</a>
    @else
      <a href="{{ route('login') }}" class="btn btn-primary w-100">تسجيل الدخول لطلب رابط جديد</a>
      <a href="{{ url('/') }}" class="btn btn-outline-secondary w-100 mt-2">العودة إلى الصفحة الرئيسية</a>
    @endauth
  </div>
@endsection
