@extends('admin.layouts.app')
@section('title','تعديل ثيم: '.$university->name)

@section('content')
<h4 class="mb-3">تعديل ثيم — {{ $university->name }}</h4>

<form action="{{ route('admin.themes.update', $university) }}" method="POST" class="row g-3">
  @csrf @method('PUT')

  <div class="col-md-4">
    <label class="form-label">اللون الأساسي (Primary)</label>
    <input type="color" name="primary_color" id="primary_color"
           class="form-control form-control-color"
           value="{{ old('primary_color', $university->primary_color ?? '#0d6efd') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label">اللون الثانوي (Secondary)</label>
    <input type="color" name="secondary_color" id="secondary_color"
           class="form-control form-control-color"
           value="{{ old('secondary_color', $university->secondary_color ?? '#6c757d') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label">وضع الثيم</label>
    <select name="theme_mode" id="theme_mode" class="form-select">
      @php $mode = old('theme_mode', $university->theme_mode ?? 'auto'); @endphp
      <option value="auto"  @selected($mode==='auto')>تلقائي (حسب النظام)</option>
      <option value="light" @selected($mode==='light')>فاتح</option>
      <option value="dark"  @selected($mode==='dark')>داكن</option>
    </select>
  </div>
  <div class="form-check form-switch">
  <input class="form-check-input" type="checkbox" name="use_default_theme" id="use_default_theme"
         value="1" {{ old('use_default_theme', $university->use_default_theme ?? false) ? 'checked' : '' }}>
  <label class="form-check-label" for="use_default_theme">استخدام الثيم الافتراضي بدل ثيم الجامعة</label>
</div>


  <div class="col-12">
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.themes.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>

<hr class="my-4">

<h6 class="mb-3">معاينة فورية</h6>
<div id="themePreview" class="p-3 border rounded" style="--p: {{ $university->primary_color ?? '#0d6efd' }}; --s: {{ $university->secondary_color ?? '#6c757d' }};">
  <div class="d-flex align-items-center gap-3">
    <div class="rounded-circle" style="width:48px;height:48px;background:var(--p)"></div>
    <div>
      <div class="fw-bold" style="color:var(--p)">عنوان بعنصر أساسي</div>
      <div class="small text-muted">نص ثانوي</div>
    </div>
    <span class="badge" style="background:var(--s)">Badge ثانوي</span>
    <button class="btn" style="background:var(--p);color:#fff;border-color:var(--p)">زر أساسي</button>
    <button class="btn btn-outline-secondary" style="border-color:var(--s);color:var(--s)">زر ثانوي</button>
  </div>
</div>

@push('scripts')
<script>
(function(){
  const root = document.documentElement;
  const preview = document.getElementById('themePreview');
  const primary  = document.getElementById('primary_color');
  const secondary= document.getElementById('secondary_color');
  const modeSel  = document.getElementById('theme_mode');

  function applyPreview(){
    preview.style.setProperty('--p', primary.value || '#0d6efd');
    preview.style.setProperty('--s', secondary.value || '#6c757d');

    // محاكاة وضع الثيم على الـ body ليتأثر CSS عام (اختياري)
    document.body.dataset.themeMode = modeSel.value; // data-theme-mode="auto|light|dark"
  }
  primary.addEventListener('input', applyPreview);
  secondary.addEventListener('input', applyPreview);
  modeSel.addEventListener('change', applyPreview);
})();
</script>
@endpush
@endsection
