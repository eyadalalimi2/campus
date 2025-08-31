<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">الاسم</label>
    <input type="text" name="name" class="form-control" required value="{{ old('name', $university->name ?? '') }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Slug</label>
    <input type="text" name="slug" class="form-control" required value="{{ old('slug', $university->slug ?? '') }}" placeholder="sanaa">
  </div>
  <div class="col-md-3">
    <label class="form-label">Code</label>
    <input type="text" name="code" class="form-control" value="{{ old('code', $university->code ?? '') }}" placeholder="SAN">
  </div>

  <div class="col-md-6">
    <label class="form-label">الشعار (PNG/JPG)</label>
    <input type="file" name="logo" class="form-control">
    @if(!empty($university?->logo_url)) <img src="{{ $university->logo_url }}" class="mt-2" style="height:48px">@endif
  </div>
  <div class="col-md-6">
    <label class="form-label">Favicon</label>
    <input type="file" name="favicon" class="form-control">
    @if(!empty($university?->favicon_url)) <img src="{{ $university->favicon_url }}" class="mt-2" style="height:32px">@endif
  </div>

  <div class="col-md-3">
    <label class="form-label">اللون الأساسي</label>
    <input type="text" name="primary_color" class="form-control" value="{{ old('primary_color', $university->primary_color ?? '#0d6efd') }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">اللون الثانوي</label>
    <input type="text" name="secondary_color" class="form-control" value="{{ old('secondary_color', $university->secondary_color ?? '#6c757d') }}">
  </div>

  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
        {{ old('is_active', $university->is_active ?? true) ? 'checked':'' }}>
      <label class="form-check-label" for="is_active">مفعل</label>
    </div>
  </div>
</div>
