<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">الجامعة</label>
    <select class="form-select" id="university_select" onchange="filterColleges()">
      <option value="">— اختر —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}">{{ $u->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">الكلية</label>
    <select name="college_id" class="form-select" id="college_select" required>
      <option value="">— اختر —</option>
      @foreach($colleges as $c)
        <option value="{{ $c->id }}" @selected(old('college_id', $major->college_id ?? '')==$c->id) data-university="{{ $c->university_id }}">
          {{ $c->name }} ({{ $c->university->name }})
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-8">
    <label class="form-label">اسم التخصص</label>
    <input type="text" name="name" class="form-control" required value="{{ old('name', $major->name ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">الرمز</label>
    <input type="text" name="code" class="form-control" value="{{ old('code', $major->code ?? '') }}">
  </div>
  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="m_active"
        {{ old('is_active', $major->is_active ?? true) ? 'checked':'' }}>
      <label class="form-check-label" for="m_active">مفعل</label>
    </div>
  </div>
</div>

@push('scripts')
<script>
function filterColleges(){
  const uni = document.getElementById('university_select').value;
  const opts = document.querySelectorAll('#college_select option[data-university]');
  opts.forEach(o => { o.hidden = (uni && o.dataset.university !== uni); });
}
</script>
@endpush
