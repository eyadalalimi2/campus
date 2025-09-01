<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>دخول الأدمن</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container" style="max-width:460px; margin-top:80px;">
    <div class="card shadow-sm">
      <div class="card-body">

        {{-- الشعار --}}
        <img src="{{ asset('storage/images/icon.png') }}" alt="شعار المناهج الأكاديمية"
             class="d-block mx-auto mb-3" style="height:64px;width:auto;">

        <h5 class="mb-3 text-center">تسجيل دخول الأدمن</h5>

        @if ($errors->any())
          <div class="alert alert-danger">
            {{ $errors->first() }}
          </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
          @csrf

          <div class="mb-3">
            <label class="form-label">البريد الإلكتروني</label>

            {{-- السطر الأصلي (معلّق مؤقتًا) --}}
            {{-- <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus> --}}

            {{-- تعبئة تلقائية مؤقتة — احذف هذا السطر لاحقًا ثم أزل التعليق عن السطر الأصلي --}}
            <input type="email" name="email" value="eyad@admin.com"
                   class="form-control" required autofocus autocomplete="username">
          </div>

          <div class="mb-3">
            <label class="form-label">كلمة المرور</label>

            {{-- السطر الأصلي (معلّق مؤقتًا) --}}
            {{-- <input type="password" name="password" class="form-control" required> --}}

            {{-- تعبئة تلقائية مؤقتة — احذف هذا السطر لاحقًا ثم أزل التعليق عن السطر الأصلي --}}
            <input type="password" name="password" value="123456789"
                   class="form-control" required autocomplete="current-password">
          </div>

          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label" for="remember">تذكرني</label>
          </div>

          <button class="btn btn-primary w-100">دخول</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
