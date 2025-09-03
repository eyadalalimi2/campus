@extends('auth.layout')
@section('title', 'تسجيل الدخول')

@section('content')
<div class="auth-container">
    <div class="auth-card shadow-lg p-4 rounded-3">
         {{-- الشعار --}}
        <img src="{{ asset('storage/images/icon.png') }}" alt="شعار المناهج الأكاديمية"
             class="d-block mx-auto mb-3" style="height:150px;width:auto;">

        <h4 class="text-center mb-4">مرحبًا بعودتك</h4>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" required>
                @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-flex justify-content-between mb-3">
                <div>
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">تذكرني</label>
                </div>
                <a href="{{ route('password.request') }}">نسيت كلمة المرور؟</a>
            </div>

            <button type="submit" class="btn btn-primary w-100">تسجيل الدخول</button>
        </form>
    </div>
</div>

<hr class="my-3">
<div class="text-center">
  <small>ليس لديك حساب؟ <a href="{{ route('register') }}">إنشاء حساب</a></small>
</div>
@endsection
