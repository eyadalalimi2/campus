@php
  // تجهيز رابط الشعار الحالي للمعاينة
  $logoSrc = null;
  if (!empty($university?->logo_path)) {
      $logoSrc = \Illuminate\Support\Facades\Storage::url($university->logo_path);
  } elseif (!empty($university?->logo_url)) {
      $logoSrc = $university->logo_url;
  }
@endphp

<div class="row g-3">
  {{-- الاسم --}}
  <div class="col-md-6">
    <label class="form-label">الاسم <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required
           value="{{ old('name', $university->name ?? '') }}" placeholder="مثال: جامعة صنعاء">
  </div>

  {{-- العنوان --}}
  <div class="col-md-6">
    <label class="form-label">العنوان <span class="text-danger">*</span></label>
    <input type="text" name="address" class="form-control" required
           value="{{ old('address', $university->address ?? '') }}" placeholder="المدينة، الشارع، المبنى">
  </div>

  {{-- رقم الهاتف --}}
  <div class="col-md-6">
    <label class="form-label">رقم الهاتف</label>
    <input type="text" name="phone" class="form-control"
           value="{{ old('phone', $university->phone ?? '') }}" placeholder="07XXXXXXXX">
  </div>

  {{-- الشعار --}}
  <div class="col-md-6">
    <label class="form-label">الشعار (PNG/JPG/WEBP) — بحد أقصى 2MB</label>
    <input type="file" name="logo" class="form-control" accept=".png,.jpg,.jpeg,.webp">
    @if ($logoSrc)
      <img src="{{ $logoSrc }}" alt="Logo" class="mt-2 rounded border"
           style="height:48px;object-fit:contain">
    @endif
  </div>

  {{-- الحالة --}}
  <div class="col-md-3 d-flex align-items-center">
    <div class="form-check mt-4">
      {{-- hidden لضمان إرسال 0 عند إلغاء التحديد --}}
      <input type="hidden" name="is_active" value="0">
      <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
             {{ old('is_active', $university->is_active ?? true) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active">مفعل</label>
    </div>
  </div>
</div>
