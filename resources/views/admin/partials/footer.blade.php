<footer class="py-3 border-top bg-white">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <small class="text-muted">© {{ date('Y') }} المنهج الطبي </small>
      <small class="text-muted ms-3">v1.0</small>
    </div>
    <div class="d-flex align-items-center gap-3 flex-wrap">
      {{-- روابط البنرات والشكاوى والإشعارات والطلبات --}}
      <div class="nav-item">
        <a href="{{ route('admin.banners.index') }}" class="nav-link text-muted p-0">
          <i class="bi bi-image" style="color:#f59e42;"></i>
          البنرات
        </a>
      </div>
      <div class="nav-item">
        <a href="{{ route('admin.complaints.index') }}" class="nav-link text-muted p-0">
          <i class="bi bi-exclamation-diamond" style="color:#e11d48;"></i>
          الشكاوى
        </a>
      </div>
      <div class="nav-item">
        <a href="{{ route('admin.notifications.index') }}" class="nav-link text-muted p-0">
          <i class="bi bi-bell" style="color:#facc15;"></i>
          الإشعارات
        </a>
      </div>
      <div class="nav-item">
        <a href="{{ route('admin.requests.index') }}" class="nav-link text-muted p-0">
          <i class="bi bi-envelope-paper" style="color:#6366f1;"></i>
          الطلبات
        </a>
      </div>
      <div class="nav-item">
        <a href="{{ route('admin.themes.index') }}" class="nav-link text-muted p-0">
          <i class="bi bi-palette" style="color:#10b981;"></i>
          إدارة الثيمات
        </a>
      </div>
      {{-- روابط الكورسات ومساعدين المحتوى --}}
      <div class="nav-item">
        <a href="{{ route('admin.courses.index') }}" class="nav-link text-muted p-0">
          <i class="bi bi-journal-bookmark" style="color:#0e7490;"></i>
          الكورسات
        </a>
      </div>
      <div class="nav-item">
        <a href="{{ route('admin.content_assistants.index') }}" class="nav-link text-muted p-0">
          <i class="bi bi-people" style="color:#0ea5e9;"></i>
          مساعدين المحتوى
        </a>
      </div>
      {{-- المدونة --}}
      <div class="nav-item">
        <a href="{{ route('admin.blogs.index') }}" class="nav-link text-muted p-0">
          <i class="bi bi-newspaper" style="color:#fb7185;"></i> المدونات
        </a>
      </div>
    </div>
  </div>
</footer>
