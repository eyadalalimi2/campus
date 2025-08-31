<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'لوحة التحكم')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <style>
    body { background: #f6f7fb; }
    .card-kpi .delta.up { color: #198754; }
    .card-kpi .delta.down { color: #dc3545; }
    .status-badge.enrolled  { background:#e7f7ee; color:#198754; }
    .status-badge.completed { background:#e7f1ff; color:#0d6efd; }
    .status-badge.dropped   { background:#fff0f0; color:#dc3545; }
    .sidebar {
      position: sticky; top: 1rem;
      min-height: calc(100vh - 2rem);
      background: #fff; border-radius: .75rem; padding: 1rem; box-shadow: 0 6px 16px rgba(0,0,0,.06);
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="{{ route('admin.dashboard') }}">Campus Admin</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topnav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div id="topnav" class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="{{ route('site.home') }}">الموقع</a></li>
          <li class="nav-item"><a class="nav-link" href="#">الإعدادات</a></li>
          <li class="nav-item"><a class="nav-link" href="#">تسجيل الخروج</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid my-4">
    <div class="row g-4">
      <aside class="col-lg-2 d-none d-lg-block">
        <div class="sidebar">
          <div class="mb-3 text-muted small">القائمة</div>
          <ul class="nav nav-pills flex-column gap-2">
            <li class="nav-item"><a class="nav-link active" href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
            <li class="nav-item"><a class="nav-link" href="#">الأقسام</a></li>
            <li class="nav-item"><a class="nav-link" href="#">المقررات</a></li>
            <li class="nav-item"><a class="nav-link" href="#">الطلاب</a></li>
            <li class="nav-item"><a class="nav-link" href="#">هيئة التدريس</a></li>
            <li class="nav-item"><a class="nav-link" href="#">التقارير</a></li>
          </ul>
        </div>
      </aside>

      <main class="col-lg-10">
        @yield('content')
      </main>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>feather.replace();</script>
  @stack('scripts')
</body>
</html>
