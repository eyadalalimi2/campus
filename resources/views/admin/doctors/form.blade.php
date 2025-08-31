@php
  $isEdit = isset($doctor) && $doctor;
@endphp

<div class="row g-3">

  <div class="col-md-6">
    <label class="form-label">الاسم</label>
    <input type="text" name="name" class="form-control" required value="{{ old('name', $doctor->name ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">النوع</label>
    <select name="type" id="type_select" class="form-select" onchange="toggleType()" required>
      <option value="university" @selected(old('type', $doctor->type ?? 'university')==='university')>جامعي</option>
      <option value="independent" @selected(old('type', $doctor->type ?? '')==='independent')>مستقل/مشهور</option>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">الهاتف</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $doctor->phone ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label">المؤهل الدراسي</label>
    <input type="text" name="degree" class="form-control" value="{{ old('degree', $doctor->degree ?? '') }}" placeholder="دكتوراه، ماجستير...">
  </div>

  <div class="col-md-2">
    <label class="form-label">سنة المؤهل</label>
    <input type="number" name="degree_year" class="form-control" value="{{ old('degree_year', $doctor->degree_year ?? '') }}" min="1900" max="{{ date('Y') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">الصورة (اختياري)</label>
    <input type="file" name="photo" class="form-control">
    @if(!empty($doctor?->photo_url))
      <img src="{{ $doctor->photo_url }}" class="mt-2" style="height:60px;border-radius:8px">
    @endif
  </div>

  <!-- قسم الجامعي -->
  <div class="col-12"><hr><strong>بيانات الجامعات (للنوع: جامعي)</strong></div>

  <div class="col-md-4 type-university">
    <label class="form-label">الجامعة</label>
    <select name="university_id" id="university_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\University::orderBy('name')->get() as $u)
        <option value="{{ $u->id }}" @selected(old('university_id', $doctor->university_id ?? '')==$u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4 type-university">
    <label class="form-label">الكلية</label>
    <select name="college_id" id="college_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\College::orderBy('name')->get() as $c)
        <option value="{{ $c->id }}" @selected(old('college_id', $doctor->college_id ?? '')==$c->id) data-university="{{ $c->university_id }}">
          {{ $c->name }} ({{ $c->university->name }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4 type-university">
    <label class="form-label">التخصص</label>
    <select name="major_id" id="major_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\Major::with('college')->orderBy('name')->get() as $m)
        <option value="{{ $m->id }}" @selected(old('major_id', $doctor->major_id ?? '')==$m->id) data-college="{{ $m->college_id }}">
          {{ $m->name }} ({{ $m->college->name }})
        </option>
      @endforeach
    </select>
  </div>

  <!-- قسم المستقل -->
  <div class="col-12"><hr><strong>تخصّصات الدكتور المستقل (يمكن اختيار عدة تخصّصات)</strong></div>
  <div class="col-md-12 type-independent">
    <label class="form-label">التخصصات</label>
    <select name="major_ids[]" id="independent_majors" class="form-select" multiple size="6">
      @foreach(\App\Models\Major::orderBy('name')->get() as $m)
        <option value="{{ $m->id }}" @selected(in_array($m->id, old('major_ids', $selectedMajors ?? [])))>
          {{ $m->name }} ({{ $m->college->name }})
        </option>
      @endforeach
    </select>
    <div class="form-text">اضغط Ctrl/⌘ لاختيار أكثر من تخصّص.</div>
  </div>

  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
        {{ old('is_active', $doctor->is_active ?? true) ? 'checked':'' }}>
      <label class="form-check-label" for="is_active">مفعل</label>
    </div>
  </div>
</div>

@push('scripts')
<script>
function toggleType(){
  const type = document.getElementById('type_select').value;
  document.querySelectorAll('.type-university').forEach(el => el.style.display = (type==='university' ? '' : 'none'));
  document.querySelectorAll('.type-independent').forEach(el => el.style.display = (type==='independent' ? '' : 'none'));
}

function filterCollegesByUniversity(){
  const uniId = document.getElementById('university_id').value;
  document.querySelectorAll('#college_id option[data-university]').forEach(o=>{
    o.hidden = (uniId && o.dataset.university !== uniId);
  });
}

function filterMajorsByCollege(){
  const colId = document.getElementById('college_id').value;
  document.querySelectorAll('#major_id option[data-college]').forEach(o=>{
    o.hidden = (colId && o.dataset.college !== colId);
  });
}

document.getElementById('type_select').addEventListener('change', toggleType);
document.getElementById('university_id').addEventListener('change', filterCollegesByUniversity);
document.getElementById('college_id').addEventListener('change', filterMajorsByCollege);

// تفعيل الحالة عند التحميل
toggleType();
filterCollegesByUniversity();
filterMajorsByCollege();
</script>
@endpush
