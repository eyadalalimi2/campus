<div class="row g-3">
  <div class="col-lg-6">
    <label class="form-label">الترم</label>
    <select name="term_id" class="form-select">
      @foreach($terms as $id => $label)
        <option value="{{ $id }}" @selected(old('term_id', optional($subject)->term_id) == $id)>{{ $label }}</option>
      @endforeach
    </select>
    @error('term_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="col-lg-6">
    <label class="form-label">المادة العامة (med_subjects)</label>
    <select name="med_subject_id" class="form-select">
      @foreach($medSubjects as $id => $name)
        <option value="{{ $id }}" @selected(old('med_subject_id', optional($subject)->med_subject_id) == $id)>{{ $name }}</option>
      @endforeach
    </select>
    @error('med_subject_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">المسار (Track)</label>
    <select name="track" class="form-select">
      @foreach(['REQUIRED','SYSTEM','CLINICAL'] as $t)
        <option value="{{ $t }}" @selected(old('track', optional($subject)->track) == $t)>{{ $t }}</option>
      @endforeach
    </select>
    @error('track')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-8">
    <label class="form-label">الاسم الظاهر (اختياري)</label>
    <input type="text" name="display_name" class="form-control" value="{{ old('display_name', optional($subject)->display_name) }}">
  </div>

  <div class="col-12">
    <label class="form-label">ملاحظات</label>
    <textarea name="notes" class="form-control" rows="3">{{ old('notes', optional($subject)->notes) }}</textarea>
  </div>

  <div class="col-md-3">
    <label class="form-label">الترتيب</label>
    <input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', optional($subject)->sort_order) }}">
  </div>

  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active_subject" @checked(old('is_active', optional($subject)->is_active))>
      <label class="form-check-label" for="is_active_subject">مفعل</label>
    </div>
  </div>
</div>