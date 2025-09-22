@php
  use App\Models\University;
  use App\Models\UniversityBranch;
  use App\Models\College;
  use App\Models\Major;
  use App\Models\PublicCollege;
  use App\Models\PublicMajor;

  $isEdit = isset($doctor) && $doctor;

  $typeVal = old('type', $doctor->type ?? 'university');
  $selUni  = old('university_id', $doctor->university_id ?? '');
  $selBr   = old('branch_id',     $doctor->branch_id     ?? '');
  $selCol  = old('college_id',    $doctor->college_id    ?? '');
  $selMaj  = old('major_id',      $doctor->major_id      ?? '');
  $selPubCol = old('public_college_id', $doctor->public_college_id ?? '');
  $selPubMaj = old('public_major_id',   $doctor->public_major_id   ?? '');

  $universities = University::orderBy('name')->get();
  $branches     = UniversityBranch::with('university')->orderBy('name')->get();
  $colleges     = College::orderBy('name')->get();
  $majorsAll    = Major::with('college')->orderBy('name')->get();
  $publicColleges = $publicColleges ?? App\Models\PublicCollege::orderBy('name')->get();
  $publicMajors   = $publicMajors   ?? App\Models\PublicMajor::orderBy('name')->get();
@endphp

<div class="row g-3">

  <div class="col-md-6">
    <label class="form-label">الاسم <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required value="{{ old('name', $doctor->name ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">النوع <span class="text-danger">*</span></label>
    <select name="type" id="type_select" class="form-select" onchange="doc_toggleType()" required>
      <option value="university"  @selected($typeVal==='university')>جامعي</option>
      <option value="independent" @selected($typeVal==='independent')>مستقل/مشهور</option>
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
    <input type="file" name="photo" class="form-control" accept=".jpg,.jpeg,.png,.webp">
    @if(!empty($doctor?->photo_url))
      <img src="{{ $doctor->photo_url }}" class="mt-2" style="height:60px;border-radius:8px" alt="photo">
    @endif
  </div>

  {{-- ===== الجامعي ===== --}}
  <div class="col-12"><hr><strong>بيانات الجامعات (للنوع: جامعي)</strong></div>
  <div class="type-university">
    <div class="row">
      <div class="col-md-3">
        <label class="form-label">الجامعة</label>
        <select name="university_id" id="university_id" class="form-select">
          <option value="">— اختر —</option>
          @foreach($universities as $u)
            <option value="{{ $u->id }}" @selected($selUni == $u->id)>{{ $u->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">الفرع</label>
        <select name="branch_id" id="branch_id" class="form-select">
          <option value="">— اختر —</option>
          @foreach($branches as $b)
            <option value="{{ $b->id }}"
                    data-university="{{ $b->university_id }}"
                    @selected($selBr == $b->id)>
              {{ $b->name }} ({{ $b->university?->name }})
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">الكلية</label>
        <select name="college_id" id="college_id" class="form-select">
          <option value="">— اختر —</option>
          @foreach($colleges as $c)
            <option value="{{ $c->id }}"
                    data-branch="{{ $c->branch_id ?? '' }}"
                    @selected($selCol == $c->id)>
              {{ $c->name }} ({{ optional($c->branch)->name ?? '—' }})
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">التخصص</label>
        <select name="major_id" id="major_id" class="form-select">
          <option value="">— اختر —</option>
          @foreach($majorsAll as $m)
            <option value="{{ $m->id }}"
                    data-college="{{ $m->college_id }}"
                    @selected($selMaj == $m->id)>
              {{ $m->name }} ({{ $m->college?->name }})
            </option>
          @endforeach
        </select>
      </div>
    </div>
  </div>

  {{-- ===== المستقل/المشهور ===== --}}
  <div class="col-12"><hr><strong>الكليات العامة والتخصصات العامة (للنوع: مستقل/مشهور)</strong></div>
  <div class="type-independent">
    <div class="row">
      <div class="col-md-6">
        <label class="form-label">الكلية العامة</label>
        <select name="public_college_id" id="public_college_id" class="form-select">
          <option value="">— اختر —</option>
          @foreach($publicColleges as $pc)
            <option value="{{ $pc->id }}" @selected($selPubCol == $pc->id)>{{ $pc->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">التخصص العام</label>
        <select name="public_major_id" id="public_major_id" class="form-select">
          <option value="">— اختر —</option>
          @foreach($publicMajors as $pm)
            <option value="{{ $pm->id }}" data-college="{{ $pm->public_college_id }}" @selected($selPubMaj == $pm->id)>{{ $pm->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>

  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input type="hidden" name="is_active" value="0">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
        {{ old('is_active', $doctor->is_active ?? true) ? 'checked':'' }}>
      <label class="form-check-label" for="is_active">مفعل</label>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function(){
  const $type = document.getElementById('type_select');
  const $uni  = document.getElementById('university_id');
  const $br   = document.getElementById('branch_id');
  const $col  = document.getElementById('college_id');
  const $maj  = document.getElementById('major_id');
  const $pubCol = document.getElementById('public_college_id');
  const $pubMaj = document.getElementById('public_major_id');

  function doc_toggleType(){
    const t = $type.value;
    document.querySelectorAll('.type-university').forEach(el => el.style.display = (t==='university' ? '' : 'none'));
    document.querySelectorAll('.type-independent').forEach(el => el.style.display = (t==='independent' ? '' : 'none'));
  }

  function filterBranchesByUniversity(){
    const uniId = $uni.value || '';
    [...$br.options].forEach(o=>{
      if(!o.value) return;
      const show = !uniId || (o.dataset.university === uniId);
      o.hidden = !show;
      if(!show && o.selected) o.selected = false;
    });
  }

  function filterCollegesByBranch(){
    const branchId = $br.value || '';
    [...$col.options].forEach(o=>{
      if(!o.value) return;
      const show = !branchId || (o.dataset.branch === branchId);
      o.hidden = !show;
      if(!show && o.selected) o.selected = false;
    });
  }

  function filterMajorsByCollege(){
    const colId = $col.value || '';
    [...$maj.options].forEach(o=>{
      if(!o.value) return;
      const show = !colId || (o.dataset.college === colId);
      o.hidden = !show;
      if(!show && o.selected) o.selected = false;
    });
  }

  function filterPublicMajorsByCollege(){
    const pubColId = $pubCol?.value || '';
    if($pubMaj){
      [...$pubMaj.options].forEach(o=>{
        if(!o.value) return;
        const show = !pubColId || (o.dataset.college === pubColId);
        o.hidden = !show;
        if(!show && o.selected) o.selected = false;
      });
    }
  }

  // cascade on change
  $type.addEventListener('change', doc_toggleType);
  $uni.addEventListener('change', function(){
    $br.value = ''; $col.value = ''; $maj.value = '';
    filterBranchesByUniversity();
    filterCollegesByBranch();
    filterMajorsByCollege();
  });
  $br.addEventListener('change', function(){
    $col.value = ''; $maj.value = '';
    filterCollegesByBranch();
    filterMajorsByCollege();
  });
  $col.addEventListener('change', filterMajorsByCollege);
  if($pubCol) $pubCol.addEventListener('change', filterPublicMajorsByCollege);

  // init
  doc_toggleType();
  filterBranchesByUniversity();
  filterCollegesByBranch();
  filterMajorsByCollege();
  filterPublicMajorsByCollege();
})();
</script>
@endpush
