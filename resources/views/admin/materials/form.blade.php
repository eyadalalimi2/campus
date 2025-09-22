@php
  $isEdit = isset($material);

  // القيم المختارة مسبقًا لفصول المادة (يُمكن تمريرها من الكنترولر)
  $selectedTermIds = old('term_ids',
      $selectedTermIds
      ?? ($isEdit ? ($material->terms?->pluck('id')->all() ?? []) : [])
  );

  // إن لم تُمرَّر $terms من الكنترولر، سنجلبها هنا كسقوط آمن (ويُفضّل تمريرها لتجنب استعلام داخل الـview)
  $terms = $terms
      ?? \App\Models\AcademicTerm::with('calendar')->orderByDesc('starts_on')->get();

  // خريطة التعريب لاسم الفصل
  $termNameMap = ['first'=>'الأول','second'=>'الثاني','summer'=>'الصيفي'];

  $scopeValue = old('scope', $material->scope ?? 'university');
@endphp

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">اسم المادة <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required
           value="{{ old('name',$material->name ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">النطاق <span class="text-danger">*</span></label>
    <select name="scope" id="scope_select" class="form-select" onchange="toggleScope()" required>
      <option value="university" @selected($scopeValue==='university')>خاص بجامعة</option>
      <option value="global"     @selected($scopeValue==='global')>عام (كل الجامعات)</option>
    </select>
  </div>

  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      {{-- لضمان إرسال 0 عند إلغاء التحديد --}}
      <input type="hidden" name="is_active" value="0">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
             {{ old('is_active',$material->is_active ?? true) ? 'checked':'' }}>
      <label class="form-check-label" for="is_active">مفعل</label>
    </div>
  </div>

  <div class="col-12 scope-university">
    <hr><strong>تحديد الجامعة/الكلية/التخصص (للمواد ذات النطاق "خاص")</strong>
  </div>

  <div class="col-md-4 scope-university">
    <label class="form-label">الجامعة</label>
    <select name="university_id" id="university_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\University::orderBy('name')->get() as $u)
        <option value="{{ $u->id }}"
          @selected(old('university_id',$material->university_id ?? '')==$u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4 scope-university">
    <label class="form-label">الكلية (اختياري)</label>
    <select name="college_id" id="college_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\College::with('university')->orderBy('name')->get() as $c)
        <option value="{{ $c->id }}"
                data-university="{{ $c->university_id }}"
                @selected(old('college_id',$material->college_id ?? '')==$c->id)>
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
        <option value="{{ $m->id }}"
                data-college="{{ $m->college_id }}"
                @selected(old('major_id',$material->major_id ?? '')==$m->id)>
          {{ $m->name }} ({{ $m->college->name }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">المستوى</label>
    <input type="number" name="level" class="form-control" min="1" max="20"
           value="{{ old('level',$material->level ?? '') }}">
  </div>

  {{-- اختيار فصول أكاديمية متعددة --}}
  <div class="col-md-9">
    <label class="form-label">الفصول الأكاديمية المرتبطة بالمادة (يمكن اختيار أكثر من فصل)</label>
    <select name="term_ids[]" id="term_ids" class="form-select" multiple size="6">
      @foreach($terms as $t)
        @php
          $label = ($t->calendar?->year_label ? $t->calendar->year_label.' - ' : '')
                   . ($termNameMap[$t->name] ?? $t->name);
        @endphp
        <option value="{{ $t->id }}" @selected(in_array($t->id,$selectedTermIds))>
          {{ $label }} ({{ $t->starts_on?->format('Y-m-d') }} → {{ $t->ends_on?->format('Y-m-d') }})
        </option>
      @endforeach
    </select>
    <div class="form-text">
      اتركها فارغة إن لم تُحدد فصولًا. يمكنك تعديلها لاحقًا.
    </div>
  </div>
</div>

@push('scripts')
<script>
function toggleScope(){
  const sc = document.getElementById('scope_select').value;
  document.querySelectorAll('.scope-university')
    .forEach(el => el.style.display = (sc==='university' ? '' : 'none'));
}
function filterCollegesByUniversity(){
  const uniId = document.getElementById('university_id').value;
  document.querySelectorAll('#college_id option[data-university]')
    .forEach(o => {
      const show = !uniId || (o.dataset.university === uniId);
      o.hidden = !show;
      if(!show && o.selected) o.selected = false;
    });
}
function filterMajorsByCollege(){
  const colId = document.getElementById('college_id').value;
  document.querySelectorAll('#major_id option[data-college]')
    .forEach(o => {
      const show = !colId || (o.dataset.college === colId);
      o.hidden = !show;
      if(!show && o.selected) o.selected = false;
    });
}

document.addEventListener('DOMContentLoaded', function(){
  toggleScope();
  filterCollegesByUniversity();
  filterMajorsByCollege();

  document.getElementById('scope_select').addEventListener('change', toggleScope);
  document.getElementById('university_id').addEventListener('change', function(){
    filterCollegesByUniversity();
    filterMajorsByCollege();
  });
  document.getElementById('college_id').addEventListener('change', filterMajorsByCollege);
});
</script>
@endpush
