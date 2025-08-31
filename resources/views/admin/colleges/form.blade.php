<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">الجامعة</label>
    <select name="university_id" class="form-select" required>
      <option value="">— اختر —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected(old('university_id', $college->university_id ?? '')==$u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">اسم الكلية</label>
    <input type="text" name="name" class="form-control" required value="{{ old('name', $college->name ?? '') }}">
  </div>
  <div class="col-md-2">
    <label class="form-label">الرمز</label>
    <input type="text" name="code" class="form-control" value="{{ old('code', $college->code ?? '') }}">
  </div>
  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="c_active"
        {{ old('is_active', $college->is_active ?? true) ? 'checked':'' }}>
      <label class="form-check-label" for="c_active">مفعل</label>
    </div>
  </div>
</div>
