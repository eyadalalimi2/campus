<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>@yield('title','لوحة الطالب')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Bootstrap RTL + Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    body{background:#f8fafc}
    .kpi-card{border:0;border-radius:1rem;color:#fff;overflow:hidden;position:relative;box-shadow:0 10px 24px rgba(0,0,0,.08)}
    .kpi-card .icon{position:absolute;inset-inline-end:12px;inset-block-start:12px;font-size:1.75rem;opacity:.25}
    .kpi-card .value{font-size:1.8rem;font-weight:800}
    .grad-a{background:linear-gradient(135deg,#5b7fff,#1aa1ff)}
    .grad-b{background:linear-gradient(135deg,#00b09b,#96c93d)}
    .grad-c{background:linear-gradient(135deg,#ff7a6e,#ffb86c)}
  </style>
  @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('student.dashboard') }}">
      <i class="bi bi-mortarboard"></i> لوحة الطالب
    </a>

    <button class="btn btn-outline-secondary d-lg-none" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#studentSidebar" aria-controls="studentSidebar">
      <i class="bi bi-list"></i>
    </button>

    <div class="ms-auto d-none d-lg-flex align-items-center gap-3">
      <span class="small text-muted">{{ auth()->user()->name }}</span>
      <form action="{{ route('logout') }}" method="POST">@csrf
        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-box-arrow-right"></i> خروج</button>
      </form>
    </div>
  </div>
</nav>

{{-- Sidebar Offcanvas --}}
<div class="offcanvas offcanvas-start offcanvas-lg" tabindex="-1" id="studentSidebar" style="width:250px">
  <div class="offcanvas-header d-lg-none">
    <h6 class="offcanvas-title mb-0">القائمة</h6>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body p-0">
    <div class="list-group list-group-flush">
      <a class="list-group-item list-group-item-action {{ request()->routeIs('student.dashboard')?'active':'' }}"
         href="{{ route('student.dashboard') }}">
        <i class="bi bi-speedometer2 me-1"></i> الرئيسية
      </a>
      {{-- يمكنك إضافة صفحات أخرى لاحقاً --}}
    </div>
  </div>
</div>

<main class="py-4">
  <div class="container">
    @yield('content')
  </div>
</main>

<footer class="border-top py-3">
  <div class="container small text-muted d-flex justify-content-between">
    <span>© {{ date('Y') }} — بوابة الطلاب</span>
    <span>نسخة تجريبية</span>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
