<footer class="py-3 border-top bg-white">
  <div class="container d-flex justify-content-between align-items-center">
    <div>
      <small class="text-muted">© {{ date('Y') }} Campus Admin</small>
      <small class="text-muted ms-3">v1.0</small>
    </div>
    <div class="d-flex align-items-center gap-3 flex-wrap">
      {{-- ميزات مستقبلية (روابط غير مفعلة حالياً) --}}
      <div class="section-title mb-0">
        <i class="bi bi-eye"></i> ميزات مستقبلية
      </div>
      <div class="nav-item">
        <a href="javascript:void(0)" class="nav-link text-muted p-0" title="عرض فقط" onclick="return false;">
          <i class="bi bi-sliders"></i>
          إعدادات النظام
        </a>
      </div>
      <div class="nav-item">
        <a href="javascript:void(0)" class="nav-link text-muted p-0" title="عرض فقط" onclick="return false;">
          <i class="bi bi-journal-medical"></i>
          مناهج الثانوية
        </a>
      </div>
      <div class="nav-item">
        <a href="javascript:void(0)" class="nav-link text-muted p-0" title="عرض فقط" onclick="return false;">
          <i class="bi bi-graph-up"></i>
          إدارة التقارير
        </a>
      </div>
      <div class="nav-item">
        <a href="{{ route('admin.themes.index') }}" class="nav-link text-muted p-0">
          <i class="bi bi-palette"></i>
          إدارة الثيمات
        </a>
      </div>
      {{-- المدونة --}}
      <div class="section-title mb-0">
        <i class="bi bi-journal-richtext"></i> المدونة
      </div>
      <div class="nav-item">
        <a href="{{ route('admin.blogs.index') }}" class="nav-link text-muted p-0">
          <i class="bi bi-newspaper"></i> المدونات
        </a>
      </div>
    </div>
  </div>
</footer>
