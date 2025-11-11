{{-- شريط التنقّل --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container">
    {{-- الشعار --}}
    <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('site.home') }}">
      <img src="{{ Storage::url('images/logo.png') }}" alt="Logo" style="height:40px">
      <span class="fw-bold">{{ $currentUniversity->name ?? 'المنهج الأكاديمي' }}</span>
    </a>

    {{-- زر قائمة الجوّال --}}
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
            aria-controls="mainNav" aria-expanded="false" aria-label="تبديل القائمة">
      <span class="navbar-toggler-icon"></span>
    </button>

    {{-- العناصر القابلة للطي --}}
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

        {{-- اختيار الجامعة + الثيم الافتراضي --}}
        <li class="nav-item">
          <form method="GET" action="{{ url()->current() }}" class="d-flex my-2 my-lg-0">
            <select name="university_id" class="form-select form-select-sm"
                    onchange="this.form.submit()" aria-label="اختر جامعة">
              <option value="default" @selected(request('university_id') === 'default')>
                الثيم الافتراضي
              </option>
              <option value="" disabled>──────────</option>
              @foreach(\App\Models\University::where('is_active',true)->orderBy('name')->get() as $u)
                <option value="{{ $u->id }}" @selected(request('university_id') == $u->id)>{{ $u->name }}</option>
              @endforeach
            </select>
          </form>
        </li>

        {{-- روابط المصادقة/لوحة الطالب --}}
        {{-- زر تطبيق الأندرويد --}}
        <li class="nav-item">
          <a class="nav-link" href="{{ route('apps.show', 'midecal') }}">
            <i class="bi bi-phone me-1"></i> تطبيق الأندرويد
          </a>
        </li>

        @guest
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">
              <i class="bi bi-box-arrow-in-right me-1"></i> تسجيل الدخول
            </a>
          </li>
          <li class="nav-item">
            <a class="btn btn-primary btn-sm ms-lg-1 my-2 my-lg-0" href="{{ route('register') }}">
              <i class="bi bi-person-plus me-1"></i> إنشاء حساب
            </a>
          </li>
        @else
          <li class="nav-item">
            <a class="btn btn-outline-primary btn-sm ms-lg-1 my-2 my-lg-0"
               href="{{ route('student.dashboard') }}">
              <i class="bi bi-speedometer2 me-1"></i> لوحة الطالب
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button"
               data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-person-circle me-1"></i>
              <span>{{ auth()->user()->name ?? 'الملف' }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="{{ route('student.dashboard') }}">
                  <i class="bi bi-grid me-2"></i> لوحة الطالب
                </a>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="dropdown-item">
                    <i class="bi bi-box-arrow-right me-2"></i> تسجيل الخروج
                  </button>
                </form>
              </li>
            </ul>
          </li>
        @endguest

      </ul>
    </div>
  </div>
</nav>
