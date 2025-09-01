<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('site.home') }}">المنهج الاكاديمي</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">لوحة التحكم</a></li>
        <li class="nav-item"><a class="nav-link" href="#">المزايا</a></li>
        <li class="nav-item"><a class="nav-link" href="#">الأسعار</a></li>
      </ul>
    </div>
  </div>
</nav>
