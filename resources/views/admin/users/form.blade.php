@php
  $isEdit = isset($user);

  $linkedInitial = old(
    'is_linked_to_university',
    (!empty($user?->university_id) || !empty($user?->college_id) || !empty($user?->major_id)) ? '1' : '0'
  );

  // مصادر السلاسل (مع بدائل في حال عدم تمريرها من الـ Controller)
  $universities = $universities ?? \App\Models\University::orderBy('name')->get();
  $branches     = $branches     ?? \App\Models\UniversityBranch::with('university:id,name')->orderBy('name')->get();  {{-- ✅ جديد --}}
  $colleges     = $colleges     ?? \App\Models\College::with('university:id,name')->orderBy('name')->get();
  $majors       = $majors       ?? \App\Models\Major::with('college:id,name')->orderBy('name')->get();
  $countries    = $countries    ?? \App\Models\Country::orderBy('name_ar')->get();

  $publicColleges = $publicColleges ?? \App\Models\PublicCollege::active()->orderBy('name')->get();
  $publicMajors   = $publicMajors   ?? \App\Models\PublicMajor::active()->with('publicCollege')->orderBy('name')->get();

  $oldPublicCollege = old('public_college_id', $user->major?->publicMajor?->public_college_id ?? '');
  $oldPublicMajor   = old('public_major_id',   $user->major?->public_major_id ?? '');
@endphp

<div class="row g-3">

  {{-- الاسم والبريد --}}
  <div class="col-md-6">
    <label class="form-label">الاسم</label>
    <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">البريد الإلكتروني</label>
    <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email ?? '') }}">
  </div>

  {{-- هاتف وصورة --}}
  <div class="col-md-6">
    <label class="form-label">رقم الهاتف</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">الصورة الشخصية (اختياري)</label>
    <input type="file" name="profile_photo" class="form-control" accept=".jpg,.jpeg,.png,.webp">
    @if (!empty($user?->profile_photo_url))
      <img src="{{ $user->profile_photo_url }}" class="mt-2 rounded" style="height:48px" alt="avatar">
    @endif
  </div>

  {{-- مبدّل الارتباط --}}
  <div class="col-12">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" role="switch" id="is_linked_to_university"
             name="is_linked_to_university" value="1"
             {{ old('is_linked_to_university', $linkedInitial) === '1' ? 'checked' : '' }}>
      <label class="form-check-label" for="is_linked_to_university">
        الطالب مرتبط بجامعة؟
      </label>
    </div>
    <div class="form-text">
      عند إلغاء الارتباط: تُخفى حقول <b>الرقم الأكاديمي</b> و<b>المستوى</b> وتظهر <b>الكليات/التخصصات العامة</b>.
    </div>
  </div>

  {{-- الكتلة المؤسسية --}}
  <div id="institutional_block" class="row g-3">
    {{-- الجامعة --}}
    <div class="col-md-3">
      <label class="form-label">الجامعة</label>
      <select name="university_id" id="university_id" class="form-select">
        <option value="">— اختر —</option>
        @foreach ($universities as $u)
          <option value="{{ $u->id }}" @selected(old('university_id', $user->university_id ?? '') == $u->id)>{{ $u->name }}</option>
        @endforeach
      </select>
    </div>

    {{-- الفرع ✅ جديد --}}
    <div class="col-md-3">
      <label class="form-label">الفرع</label>
      <select name="branch_id" id="branch_id" class="form-select">
        <option value="">— اختر —</option>
        @foreach ($branches as $b)
          <option value="{{ $b->id }}"
                  data-university="{{ $b->university_id }}"
                  @selected(old('branch_id', $user->branch_id ?? '') == $b->id)>
            {{ $b->name }} ({{ $b->university?->name }})
          </option>
        @endforeach
      </select>
    </div>

    {{-- الكلية (مرتبطة بالفرع) --}}
    <div class="col-md-3">
      <label class="form-label">الكلية</label>
      <select name="college_id" id="college_id" class="form-select">
        <option value="">— اختر —</option>
        @foreach ($colleges as $c)
          <option value="{{ $c->id }}"
                  data-branch="{{ $c->branch_id }}"
                  @selected(old('college_id', $user->college_id ?? '') == $c->id)>
            {{ $c->name }}
          </option>
        @endforeach
      </select>
    </div>

    {{-- التخصص --}}
    <div class="col-md-3">
      <label class="form-label">التخصص</label>
      <select name="major_id" id="major_id" class="form-select">
        <option value="">— اختر —</option>
        @foreach ($majors as $m)
          <option value="{{ $m->id }}"
                  data-college="{{ $m->college_id }}"
                  @selected(old('major_id', $user->major_id ?? '') == $m->id)>
            {{ $m->name }} ({{ $m->college?->name }})
          </option>
        @endforeach
      </select>
    </div>

    {{-- الأكاديمي: رقم/مستوى --}}
    <div id="academic_fields_block" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">الرقم الأكاديمي</label>
        <input type="text" name="student_number" class="form-control" value="{{ old('student_number', $user->student_number ?? '') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">المستوى</label>
        <input type="number" name="level" class="form-control" min="1" max="20" value="{{ old('level', $user->level ?? '') }}">
      </div>
    </div>
  </div>

  {{-- الكتلة العامة --}}
  <div id="public_taxonomy_block" class="row g-3">
    <div class="col-md-6">
      <label class="form-label">الكلية العامة</label>
      <select name="public_college_id" id="public_college_id" class="form-select">
        <option value="">— اختر —</option>
        @foreach ($publicColleges as $pc)
          <option value="{{ $pc->id }}" @selected((string)$oldPublicCollege === (string)$pc->id)>{{ $pc->name }}</option>
        @endforeach
      </select>
      <div class="form-text">كلية عامة غير مرتبطة بجامعة محددة.</div>
    </div>

    <div class="col-md-6">
      <label class="form-label">التخصص العام</label>
      <select name="public_major_id" id="public_major_id" class="form-select">
        <option value="">— اختر —</option>
        @foreach ($publicMajors as $pm)
          <option value="{{ $pm->id }}"
                  data-public-college="{{ $pm->public_college_id }}"
                  @selected((string)$oldPublicMajor === (string)$pm->id)>
            {{ $pm->publicCollege?->name }} — {{ $pm->name }}
          </option>
        @endforeach
      </select>
      <div class="form-text">سيُستخدم لتخصيص المحتوى العام للطالب غير المرتبط بجامعة.</div>
    </div>
  </div>

  {{-- الجنس/الدولة/الحالة --}}
  <div class="col-md-3">
    <label class="form-label">الجنس</label>
    @php $g = old('gender', $user->gender ?? ''); @endphp
    <select name="gender" class="form-select">
      <option value="">— اختر —</option>
      <option value="male" @selected($g === 'male')>ذكر</option>
      <option value="female" @selected($g === 'female')>أنثى</option>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">الدولة</label>
    <select name="country_id" class="form-select" required>
      <option value="">— اختر —</option>
      @foreach ($countries as $country)
        <option value="{{ $country->id }}"
                @selected(old('country_id', $user->country_id ?? 1) == $country->id)>
          {{ $country->name_ar }} @if($country->iso2) ({{ $country->iso2 }}) @endif
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">الحالة</label>
    @php $st = old('status', $user->status ?? 'active'); @endphp
    <select name="status" class="form-select" required>
      <option value="active" @selected($st === 'active')>نشط</option>
      <option value="suspended" @selected($st === 'suspended')>موقوف</option>
      <option value="graduated" @selected($st === 'graduated')>متخرج</option>
    </select>
  </div>

  {{-- كلمة المرور --}}
  <div class="col-md-6">
    <label class="form-label">كلمة المرور {{ $isEdit ? '(اتركها فارغة إن لم ترد تغييرها)' : '' }}</label>
    <input type="password" name="password" class="form-control" {{ $isEdit ? '' : 'required' }} minlength="8">
  </div>
  <div class="col-md-6">
    <label class="form-label">تأكيد كلمة المرور</label>
    <input type="password" name="password_confirmation" class="form-control" {{ $isEdit ? '' : 'required' }} minlength="8">
  </div>

</div>

@push('scripts')
<script>
(function(){
  const $linked = document.getElementById('is_linked_to_university');

  // مؤسسي
  const $instBlk = document.getElementById('institutional_block');
  const $uni     = document.getElementById('university_id');
  const $br      = document.getElementById('branch_id');   // ✅ جديد
  const $col     = document.getElementById('college_id');
  const $maj     = document.getElementById('major_id');

  // عام
  const $pubBlk  = document.getElementById('public_taxonomy_block');
  const $pubCol  = document.getElementById('public_college_id');
  const $pubMaj  = document.getElementById('public_major_id');

  function filterBranchesByUniversity() {
    const uniId = $uni.value;
    document.querySelectorAll('#branch_id option[data-university]').forEach(o => {
      const show = !uniId || (o.dataset.university === uniId);
      o.hidden = !show;
      if(!show && o.selected) o.selected = false;
    });
  }

  function filterCollegesByBranch() {
    const brId = $br.value;
    document.querySelectorAll('#college_id option[data-branch]').forEach(o => {
      const show = !brId || (o.dataset.branch === brId);
      o.hidden = !show;
      if(!show && o.selected) o.selected = false;
    });
  }

  function filterMajorsByCollege() {
    const colId = $col.value;
    document.querySelectorAll('#major_id option[data-college]').forEach(o => {
      const show = !colId || (o.dataset.college === colId);
      o.hidden = !show;
      if(!show && o.selected) o.selected = false;
    });
  }

  function filterPublicMajorsByPublicCollege() {
    const pc = $pubCol.value;
    [...$pubMaj.options].forEach(o => {
      if (!o.value) { o.hidden = false; return; }
      const match = !pc || (o.dataset.publicCollege === pc);
      o.hidden = !match;
      if (!match && o.selected) o.selected = false;
    });
  }

  function toggleModeUI() {
    const linked = $linked.checked;

    // مؤسسي
    $instBlk.style.display = linked ? '' : 'none';
    [$uni,$br,$col,$maj].forEach(el => { el.disabled = !linked; if(!linked){ el.value=''; } });

    // عام
    $pubBlk.style.display = linked ? 'none' : '';
    [$pubCol,$pubMaj].forEach(el => { el.disabled = linked; if(linked){ el.value=''; } });

    document.getElementById('academic_fields_block').style.display = linked ? '' : 'none';

    if (linked) {
      filterBranchesByUniversity();
      filterCollegesByBranch();
      filterMajorsByCollege();
    } else {
      filterPublicMajorsByPublicCollege();
    }
  }

  // init
  toggleModeUI();
  filterBranchesByUniversity();
  filterCollegesByBranch();
  filterMajorsByCollege();
  filterPublicMajorsByPublicCollege();

  // listeners
  $linked.addEventListener('change', toggleModeUI);
  $uni.addEventListener('change', function(){
    filterBranchesByUniversity();
    filterCollegesByBranch();
    filterMajorsByCollege();
  });
  $br.addEventListener('change', function(){
    filterCollegesByBranch();
    filterMajorsByCollege();
  });
  $col.addEventListener('change', filterMajorsByCollege);
  $pubCol.addEventListener('change', filterPublicMajorsByPublicCollege);
})();
</script>
@endpush
