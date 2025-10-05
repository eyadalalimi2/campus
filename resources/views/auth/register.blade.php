@extends('auth.layout')
@section('title','إنشاء حساب')

@section('content')
@if($errors->any())
  <div class="alert alert-danger small">{{ $errors->first() }}</div>
@endif
 {{-- الشعار --}}
        <img src="{{ asset('storage/images/icon.png') }}" alt="شعار المناهج الأكاديمية"
             class="d-block mx-auto mb-3" style="height:100px;width:auto;">

<form method="POST" action="{{ route('register.post') }}" class="vstack gap-3">
  @csrf
  <div>
    <label class="form-label">الاسم الكامل</label>
    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
  </div>
  <div class="mb-2">
    <label class="form-label">هل أنت مرتبط بجامعة؟</label>
    <input type="checkbox" id="is_linked_to_university" name="is_linked_to_university" value="1" checked>
    <span class="small">عند إلغاء الارتباط ستظهر الحقول العامة فقط.</span>
  </div>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">البريد الإلكتروني</label>
      <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">الهاتف (اختياري)</label>
      <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
    </div>
  </div>

  <div id="institutional_block">
    <div class="row g-3">
      <div class="col-md-3">
        <label class="form-label">الجامعة</label>
        <select name="university_id" id="reg_uni" class="form-select">
          <option value="">— اختر —</option>
          @foreach(\App\Models\University::orderBy('name')->get() as $u)
            <option value="{{ $u->id }}" @selected(old('university_id')==$u->id)>{{ $u->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">الفرع</label>
        <select name="branch_id" id="reg_branch" class="form-select">
          <option value="">— اختر —</option>
          @foreach(\App\Models\UniversityBranch::orderBy('name')->get() as $b)
            <option value="{{ $b->id }}" data-university="{{ $b->university_id }}" @selected(old('branch_id')==$b->id)>{{ $b->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">الكلية</label>
        <select name="college_id" id="reg_college" class="form-select">
          <option value="">— اختر —</option>
          @foreach(\App\Models\College::orderBy('name')->get() as $c)
            <option value="{{ $c->id }}" data-branch="{{ $c->branch_id }}" @selected(old('college_id')==$c->id)>{{ $c->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="form-label">التخصص</label>
        <select name="major_id" id="reg_major" class="form-select">
          <option value="">— اختر —</option>
          @foreach(\App\Models\Major::orderBy('name')->get() as $m)
            <option value="{{ $m->id }}" data-college="{{ $m->college_id }}" @selected(old('major_id')==$m->id)>{{ $m->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <div class="row g-3 mt-2">
      <div class="col-md-6">
        <label class="form-label">الرقم الأكاديمي</label>
        <input type="text" name="student_number" class="form-control" value="{{ old('student_number') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">المستوى</label>
        <input type="number" name="level" class="form-control" min="1" max="20" value="{{ old('level') }}">
      </div>
      <div class="col-md-6">
        <label class="form-label">الفصل الحالي</label>
        <input type="number" name="current_term" class="form-control" min="1" max="20" value="{{ old('current_term') }}">
      </div>
    </div>
  </div>

  <div id="public_taxonomy_block" style="display:none">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">الكلية العامة</label>
        <select name="public_college_id" id="public_college_id" class="form-select">
          <option value="">— اختر —</option>
          @foreach(\App\Models\PublicCollege::active()->orderBy('name')->get() as $pc)
            <option value="{{ $pc->id }}" @selected(old('public_college_id')==$pc->id)>{{ $pc->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label class="form-label">التخصص العام</label>
        <select name="public_major_id" id="public_major_id" class="form-select">
          <option value="">— اختر —</option>
          @foreach(\App\Models\PublicMajor::active()->orderBy('name')->get() as $pm)
            <option value="{{ $pm->id }}" data-public-college="{{ $pm->public_college_id }}" @selected(old('public_major_id')==$pm->id)>{{ $pm->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>


  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">الدولة</label>
      @php $country = old('country','اليمن'); @endphp
      <select name="country" class="form-select">
        @foreach(['اليمن','السعودية','الإمارات','عُمان','قطر','البحرين','مصر','الأردن','العراق','فلسطين','سوريا','لبنان','الكويت','ليبيا','المغرب','الجزائر','تونس','السودان','موريتانيا','جيبوتي','الصومال','جزر القمر'] as $c)
          <option value="{{ $c }}" @selected($country===$c)>{{ $c }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">الجنس</label>
      <select name="gender" class="form-select">
        <option value="">— اختر —</option>
        <option value="male"   @selected(old('gender')==='male')>ذكر</option>
        <option value="female" @selected(old('gender')==='female')>أنثى</option>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">المستوى (اختياري)</label>
      <input type="number" name="level" class="form-control" min="1" max="20" value="{{ old('level') }}">
    </div>
  </div>

  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">كلمة المرور</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">تأكيد كلمة المرور</label>
      <input type="password" name="password_confirmation" class="form-control" required>
    </div>
  </div>

  <button class="btn btn-primary w-100">إنشاء الحساب</button>
</form>

<hr>
<div class="text-center small">
  لديك حساب؟ <a href="{{ route('login') }}">تسجيل الدخول</a>
</div>

@push('scripts')
<script>
  const $linked = document.getElementById('is_linked_to_university');
  const $instBlk = document.getElementById('institutional_block');
  const $uni     = document.getElementById('reg_uni');
  const $br      = document.getElementById('reg_branch');
  const $col     = document.getElementById('reg_college');
  const $maj     = document.getElementById('reg_major');
  const $pubBlk  = document.getElementById('public_taxonomy_block');
  const $pubCol  = document.getElementById('public_college_id');
  const $pubMaj  = document.getElementById('public_major_id');

  function filterBranchesByUniversity() {
    const uniId = $uni.value;
    document.querySelectorAll('#reg_branch option[data-university]').forEach(o => {
      const show = !uniId || (o.dataset.university === uniId);
      o.hidden = !show;
      if(!show && o.selected) o.selected = false;
    });
  }
  function filterCollegesByBranch() {
    const brId = $br.value;
    document.querySelectorAll('#reg_college option[data-branch]').forEach(o => {
      const show = !brId || (o.dataset.branch === brId);
      o.hidden = !show;
      if(!show && o.selected) o.selected = false;
    });
  }
  function filterMajorsByCollege() {
    const colId = $col.value;
    document.querySelectorAll('#reg_major option[data-college]').forEach(o => {
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
    $instBlk.style.display = linked ? '' : 'none';
    [$uni,$br,$col,$maj].forEach(el => { el.disabled = !linked; if(!linked){ el.value=''; } });
    $pubBlk.style.display = linked ? 'none' : '';
    [$pubCol,$pubMaj].forEach(el => { el.disabled = linked; if(linked){ el.value=''; } });
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
</script>
@endpush
@endsection
