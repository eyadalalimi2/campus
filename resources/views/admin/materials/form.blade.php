@php $isEdit = isset($material); @endphp
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">اسم المادة</label>
    <input type="text" name="name" class="form-control" required value="{{ old('name',$material->name ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">النطاق</label>
    @php $sc = old('scope',$material->scope ?? 'university'); @endphp
    <select name="scope" id="scope_select" class="form-select" onchange="toggleScope()" required>
      <option value="university" @selected($sc==='university')>خاص بجامعة</option>
      <option value="global" @selected($sc==='global')>عام (كل الجامعات)</option>
    </select>
  </div>

  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active',$material->is_active ?? true) ? 'checked':'' }}>
      <label class="form-check-label" for="is_active">مفعل</label>
    </div>
  </div>

  <div class="col-12 scope-university"><hr><strong>تحديد الجامعة/الكلية/التخصص (للمحتوى الخاص)</strong></div>

  <div class="col-md-4 scope-university">
    <label class="form-label">الجامعة</label>
    <select name="university_id" id="university_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\University::orderBy('name')->get() as $u)
        <option value="{{ $u->id }}" @selected(old('university_id',$material->university_id ?? '')==$u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4 scope-university">
    <label class="form-label">الكلية (اختياري)</label>
    <select name="college_id" id="college_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\College::with('university')->orderBy('name')->get() as $c)
        <option value="{{ $c->id }}" @selected(old('college_id',$material->college_id ?? '')==$c->id) data-university="{{ $c->university_id }}">
          {{ $c->name }} ({{ $c->university->name }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4 scope-university">
    <label class="form-label">التخصص (اختياري)</label>
    <select name="major_id" id="major_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\Major::with('college')->orderBy('name')->get() as $m)
        <option value="{{ $m->id }}" @selected(old('major_id',$material->major_id ?? '')==$m->id) data-college="{{ $m->college_id }}">
          {{ $m->name }} ({{ $m->college->name }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">المستوى</label>
    <input type="number" name="level" class="form-control" min="1" max="20" value="{{ old('level',$material->level ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">الفترة/الترم</label>
    @php $term = old('term',$material->term ?? ''); @endphp
    <select name="term" id="term" class="form-select">
      <option value="">— اختر —</option>
      <option value="first"  @selected($term==='first')>الأول</option>
      <option value="second" @selected($term==='second')>الثاني</option>
      <option value="summer" @selected($term==='summer')>الصيفي</option>
    </select>
  </div>
</div>

@push('scripts')
<script>
function toggleScope(){
  const sc = document.getElementById('scope_select').value;
  document.querySelectorAll('.scope-university').forEach(el => el.style.display = (sc==='university' ? '' : 'none'));
}
function filterCollegesByUniversity(){
  const uniId = document.getElementById('university_id').value;
  document.querySelectorAll('#college_id option[data-university]').forEach(o => o.hidden = (uniId && o.dataset.university !== uniId));
}
function filterMajorsByCollege(){
  const colId = document.getElementById('college_id').value;
  document.querySelectorAll('#major_id option[data-college]').forEach(o => o.hidden = (colId && o.dataset.college !== colId));
}
document.getElementById('scope_select').addEventListener('change', toggleScope);
document.getElementById('university_id').addEventListener('change', function(){ filterCollegesByUniversity(); filterMajorsByCollege(); });
document.getElementById('college_id').addEventListener('change', filterMajorsByCollege);

// تهيئة أولية
toggleScope(); filterCollegesByUniversity(); filterMajorsByCollege();
</script>
@endpush
