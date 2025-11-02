<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">السنة</label>
    <select name="year_id" class="form-select">
      @foreach($years as $id => $label)
        <option value="{{ $id }}" @selected(old('year_id', optional($term)->year_id) == $id)>{{ $label }}</option>
      @endforeach
    </select>
    @error('year_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">رقم الترم (1..3)</label>
    <input type="number" min="1" max="3" name="term_number" class="form-control" value="{{ old('term_number', optional($term)->term_number) }}">
    @error('term_number')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">الترتيب</label>
    <input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', optional($term)->sort_order) }}">
  </div>

  <div class="col-12">
    <div class="form-check">
      <input type="hidden" name="is_active" value="0">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active_term" @checked(old('is_active', optional($term)->is_active))>
      <label class="form-check-label" for="is_active_term">مفعل</label>
    </div>
  </div>

  <div class="col-md-6">
    <label class="form-label">صورة الترم (اختياري)</label>
    <input type="file" name="image" class="form-control" accept="image/*">
    @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  @if(optional($term)->image_url)
    <div class="col-md-6">
      <label class="form-label d-block">المعاينة الحالية</label>
      <img src="{{ $term->image_url }}" alt="term image" class="img-thumbnail" style="max-height:120px">
    </div>
  @endif
</div>