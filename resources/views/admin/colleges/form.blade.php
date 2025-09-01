<div class="row g-3">
  {{-- الجامعة --}}
  <div class="col-md-6">
    <label class="form-label">الجامعة</label>
    <select name="university_id" class="form-select" required>
      <option value="">— اختر —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected(old('university_id', $college->university_id ?? '') == $u->id)>
          {{ $u->name }}
        </option>
      @endforeach
    </select>
  </div>

  {{-- اسم الكلية --}}
  <div class="col-md-6">
    <label class="form-label">اسم الكلية</label>
    <input type="text" name="name" class="form-control" required
           value="{{ old('name', $college->name ?? '') }}">
  </div>

  {{-- الحالة --}}
  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      {{-- ملاحظة: أضف hidden لضمان إرسال قيمة عند إلغاء التحديد --}}
      <input type="hidden" name="is_active" value="0">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="c_active"
        {{ old('is_active', $college->is_active ?? true) ? 'checked' : '' }}>
      <label class="form-check-label" for="c_active">مفعل</label>
    </div>
  </div>
</div>
