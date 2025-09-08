@php
  $isEdit = isset($batch);
  $statusVal = old('status', $batch->status ?? 'draft');
@endphp

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">اسم الدفعة <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required
      value="{{ old('name', $batch->name ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">الخطة <span class="text-danger">*</span></label>
    <select name="plan_id" class="form-select" required>
      <option value="">— اختر —</option>
      @foreach($plans as $p)
        <option value="{{ $p->id }}" @selected(old('plan_id', $batch->plan_id ?? '') == $p->id)>{{ $p->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-12">
    <label class="form-label">ملاحظات</label>
    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $batch->notes ?? '') }}</textarea>
  </div>

  <div class="col-md-4">
    <label class="form-label">الجامعة</label>
    <select name="university_id" id="uni_select" class="form-select">
      <option value="">— بدون —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected(old('university_id', $batch->university_id ?? '') == $u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">الكلية</label>
    <select name="college_id" id="col_select" class="form-select">
      <option value="">— بدون —</option>
      @foreach($colleges as $c)
        <option value="{{ $c->id }}"
                data-university="{{ $c->university_id }}"
                @selected(old('college_id', $batch->college_id ?? '') == $c->id)>
          {{ $c->name }} ({{ $c->university->name }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">التخصص</label>
    <select name="major_id" id="maj_select" class="form-select">
      <option value="">— بدون —</option>
      @foreach($majors as $m)
        <option value="{{ $m->id }}"
                data-college="{{ $m->college_id }}"
                @selected(old('major_id', $batch->major_id ?? '') == $m->id)>
          {{ $m->name }} ({{ $m->college->name }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">الكمية <span class="text-danger">*</span></label>
    <input type="number" name="quantity" class="form-control" min="1" max="100000"
      value="{{ old('quantity', $batch->quantity ?? 1) }}" required>
  </div>

  <div class="col-md-3">
    <label class="form-label">الحالة</label>
    <select name="status" class="form-select">
      <option value="draft"    @selected($statusVal==='draft')>مسودة</option>
      <option value="active"   @selected($statusVal==='active')>مفعّلة</option>
      <option value="disabled" @selected($statusVal==='disabled')>موقوفة</option>
      <option value="archived" @selected($statusVal==='archived')>مؤرشفة</option>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">طول الكود</label>
    <input type="number" name="code_length" class="form-control" min="8" max="32"
      value="{{ old('code_length', $batch->code_length ?? 14) }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">بادئة الكود (اختياري)</label>
    <input type="text" name="code_prefix" class="form-control" maxlength="24"
      value="{{ old('code_prefix', $batch->code_prefix ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">أيام الاشتراك</label>
    <input type="number" name="duration_days" class="form-control" min="1" max="2000"
      value="{{ old('duration_days', $batch->duration_days ?? 365) }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">سياسة البدء</label>
    @php $sp = old('start_policy', $batch->start_policy ?? 'on_redeem'); @endphp
    <select name="start_policy" id="start_policy" class="form-select">
      <option value="on_redeem"   @selected($sp==='on_redeem')>عند التفعيل</option>
      <option value="fixed_start" @selected($sp==='fixed_start')>موعد ثابت</option>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">تاريخ البدء (إن كان ثابتًا)</label>
    <input type="date" name="starts_on" class="form-control"
      value="{{ old('starts_on', optional($batch->starts_on ?? null)->format('Y-m-d')) }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">صالح من</label>
    <input type="datetime-local" name="valid_from" class="form-control"
      value="{{ old('valid_from', optional($batch->valid_from ?? null)?->format('Y-m-d\TH:i')) }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">صالح حتى</label>
    <input type="datetime-local" name="valid_until" class="form-control"
      value="{{ old('valid_until', optional($batch->valid_until ?? null)?->format('Y-m-d\TH:i')) }}">
  </div>
</div>

@push('scripts')
<script>
function filterCollegesByUniversity(){
  const uni = document.getElementById('uni_select').value;
  document.querySelectorAll('#col_select option[data-university]').forEach(o=>{
    const show = !uni || o.dataset.university === uni;
    o.hidden = !show;
    if(!show && o.selected) o.selected = false;
  });
  filterMajorsByCollege();
}
function filterMajorsByCollege(){
  const col = document.getElementById('col_select').value;
  document.querySelectorAll('#maj_select option[data-college]').forEach(o=>{
    const show = !col || o.dataset.college === col;
    o.hidden = !show;
    if(!show && o.selected) o.selected = false;
  });
}
document.getElementById('uni_select').addEventListener('change', filterCollegesByUniversity);
document.getElementById('col_select').addEventListener('change', filterMajorsByCollege);
filterCollegesByUniversity(); // init cascade
</script>
@endpush
