<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>لوحة التحكم</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-white">
  <nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
      <span class="navbar-brand mb-0 h1">لوحة التحكم</span>
      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button class="btn btn-outline-danger btn-sm">خروج</button>
      </form>
    </div>
  </nav>

  <div class="container py-4">
    <h4 class="mb-3">مرحبًا بك في لوحة التحكم</h4>
    <p class="text-muted">هذه صفحة تجريبية للتأكد من نجاح تسجيل الدخول للأدمن.</p>
    <a class="btn btn-link" href="{{ url('/') }}">الانتقال للموقع العام</a>
  </div>
</body>
</html>
