<nav class="navbar navbar-expand-lg bg-white shadow-sm">
  <style>
    /* تكبير مساحة عرض الشعار */
    .navbar .brand-xl{font-size:2rem;line-height:1.2;font-weight:700;padding:.65rem 0;margin-inline-end:1.5rem;white-space:nowrap;}
    @media(min-width:992px){.navbar .brand-xl{font-size:2.25rem;}}
  </style>
  <div class="container">
    <a class="navbar-brand fw-bold brand-xl" href="{{ route('site.home') }}" aria-label="المنهج الأكاديمي - الرئيسية">المنهج الاكاديمي</a>
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
