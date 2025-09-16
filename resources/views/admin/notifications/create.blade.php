@extends('admin.layouts.app')
@section('title','إرسال إشعار')

@section('content')
@php
  $typeLabels = [
    'content_created' => 'تم إنشاء محتوى',
    'content_updated' => 'تم تحديث محتوى',
    'content_deleted' => 'تم حذف محتوى',
    'asset_created'   => 'تم إنشاء مادة تعليمية',
    'asset_updated'   => 'تم تحديث مادة تعليمية',
    'asset_deleted'   => 'تم حذف مادة تعليمية',
    'system'          => 'نظام',
    'other'           => 'أخرى',
  ];
@endphp

<h1 class="h4 mb-3">إرسال إشعار</h1>

@if($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
  </div>
@endif
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.notifications.store') }}" class="card card-body" id="notifyForm">
  @csrf
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label required">العنوان</label>
      <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">الرابط (اختياري)</label>
      <input type="url" class="form-control" name="action_url" value="{{ old('action_url') }}" placeholder="رابط فتح عند النقر">
    </div>
    <div class="col-12">
      <label class="form-label required">النص</label>
      <textarea class="form-control" rows="4" name="body" required>{{ old('body') }}</textarea>
    </div>

    <div class="col-md-3">
      <label class="form-label">نوع الإشعار</label>
      <select name="type" class="form-select">
        <option value="">نظام (افتراضي)</option>
        @foreach($typeLabels as $key=>$label)
          <option value="{{ $key }}" @selected(old('type')===$key)>{{ $label }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">مرفق صورة (اختياري)</label>
      <input type="url" class="form-control" name="image_url" value="{{ old('image_url') }}" placeholder="رابط صورة">
    </div>
    <div class="col-md-3">
      <label class="form-label">إرسال فوري؟</label>
      <select name="dispatch_now" class="form-select">
        <option value="1" @selected(old('dispatch_now','1')==='1')>نعم</option>
        <option value="0" @selected(old('dispatch_now')==='0')>لاحقاً</option>
      </select>
    </div>
  </div>

  <hr>

  <div class="row g-3">
    <div class="col-md-3">
      <label class="form-label required">نوع الهدف</label>
      <select name="target_type" id="target_type" class="form-select" required>
        <option value="all"  @selected(old('target_type')==='all')>كل المستخدمين</option>
        <option value="users" @selected(old('target_type')==='users')>مستخدمون محددون</option>
        <option value="university" @selected(old('target_type')==='university')>جامعة</option>
        <option value="college" @selected(old('target_type')==='college')>كلية (ضمن الجامعة)</option>
        <option value="major" @selected(old('target_type')==='major')>تخصص (ضمن الكلية/الجامعة)</option>
      </select>
    </div>

    {{-- users multi --}}
    <div class="col-md-9 target-block d-none" id="block-users">
      <label class="form-label">اختر مستخدمين</label>
      <div class="d-flex gap-2">
        <input type="text" class="form-control" id="userSearch" placeholder="ابحث بالاسم/البريد">
        <button type="button" class="btn btn-outline-secondary" id="btnSearchUsers">بحث</button>
      </div>
      <small class="text-muted">يمكن اختيار أكثر من مستخدم.</small>
      <select name="user_ids[]" id="user_ids" class="form-select mt-2" multiple size="8"></select>
    </div>

    {{-- سلسلة: جامعة -> كلية -> تخصص --}}
    <div class="col-md-3 target-block d-none" id="block-university">
      <label class="form-label required">الجامعة</label>
      <select name="university_id" id="university_id" class="form-select"></select>
    </div>
    <div class="col-md-3 target-block d-none" id="block-college">
      <label class="form-label">الكلية</label>
      <select name="college_id" id="college_id" class="form-select" disabled></select>
    </div>
    <div class="col-md-3 target-block d-none" id="block-major">
      <label class="form-label">التخصص</label>
      <select name="major_id" id="major_id" class="form-select" disabled></select>
    </div>
  </div>

  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary">إرسال</button>
    <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">إلغاء</a>
  </div>
</form>

<script>
(function(){
  const targetTypeEl = document.getElementById('target_type');

  const blkUsers = document.getElementById('block-users');
  const blkUniv  = document.getElementById('block-university');
  const blkCol   = document.getElementById('block-college');
  const blkMaj   = document.getElementById('block-major');

  const userList = document.getElementById('user_ids');
  const userSearch = document.getElementById('userSearch');
  const btnSearchUsers = document.getElementById('btnSearchUsers');

  const univEl = document.getElementById('university_id');
  const colEl  = document.getElementById('college_id');
  const majEl  = document.getElementById('major_id');

  const routes = {
    users:        '{{ route("admin.notifications.options.users") }}',
    universities: '{{ route("admin.notifications.options.universities") }}',
    colleges:     '{{ route("admin.notifications.options.colleges") }}', // ?university_id=
    majors:       '{{ route("admin.notifications.options.majors") }}',   // ?college_id=
  };

  function resetSelect(el, placeholder='— اختر —', disabled=false) {
    el.innerHTML = `<option value="">${placeholder}</option>`;
    el.disabled = !!disabled;
  }

  function showBlocks(){
    const t = targetTypeEl.value;
    [blkUsers, blkUniv, blkCol, blkMaj].forEach(el => el.classList.add('d-none'));

    if (t === 'users') {
      blkUsers.classList.remove('d-none');

    } else if (t === 'university') {
      blkUniv.classList.remove('d-none');
      blkCol.classList.remove('d-none'); // اختياري لتضييق داخل الجامعة
      blkMaj.classList.remove('d-none'); // اختياري
      loadUniversities().then(()=>{
        resetSelect(colEl, '— (اختياري) اختر كلية —', true);
        resetSelect(majEl, '— (اختياري) اختر تخصص —', true);
      });

    } else if (t === 'college') {
      blkUniv.classList.remove('d-none');
      blkCol.classList.remove('d-none');
      loadUniversities().then(()=>{
        resetSelect(colEl, '— اختر كلية —', true);
        resetSelect(majEl, '— (اختياري) اختر تخصص —', true);
      });

    } else if (t === 'major') {
      blkUniv.classList.remove('d-none');
      blkCol.classList.remove('d-none');
      blkMaj.classList.remove('d-none');
      loadUniversities().then(()=>{
        resetSelect(colEl, '— اختر كلية —', true);
        resetSelect(majEl, '— اختر تخصص —', true);
      });
    }
  }

  async function fetchJson(url){
    const res = await fetch(url, {headers: {'X-Requested-With':'XMLHttpRequest'}});
    return await res.json();
  }

  async function loadUniversities(){
    resetSelect(univEl, '— اختر جامعة —');
    const items = await fetchJson(routes.universities);
    items.forEach(i => {
      const op = document.createElement('option');
      op.value = i.id; op.textContent = i.name;
      univEl.appendChild(op);
    });
  }

  async function loadCollegesByUniversity(univId){
    resetSelect(colEl, '— اختر كلية —', true);
    resetSelect(majEl, '— اختر تخصص —', true);
    if (!univId) return;
    const items = await fetchJson(routes.colleges + '?university_id=' + encodeURIComponent(univId));
    resetSelect(colEl, '— اختر كلية —', false);
    items.forEach(i => {
      const op = document.createElement('option');
      op.value = i.id; op.textContent = i.name;
      colEl.appendChild(op);
    });
  }

  async function loadMajorsByCollege(collegeId){
    resetSelect(majEl, '— اختر تخصص —', true);
    if (!collegeId) return;
    const items = await fetchJson(routes.majors + '?college_id=' + encodeURIComponent(collegeId));
    resetSelect(majEl, '— اختر تخصص —', false);
    items.forEach(i => {
      const op = document.createElement('option');
      op.value = i.id; op.textContent = i.name;
      majEl.appendChild(op);
    });
  }

  async function searchUsers(){
    const params = new URLSearchParams();
    if (userSearch.value) params.append('q', userSearch.value);
    const items = await fetchJson(routes.users + (params.toString() ? ('?'+params.toString()) : ''));
    userList.innerHTML = '';
    items.forEach(i => {
      const op = document.createElement('option');
      op.value = i.id; op.textContent = i.text;
      userList.appendChild(op);
    });
  }

  targetTypeEl.addEventListener('change', showBlocks);
  univEl?.addEventListener('change', (e)=> loadCollegesByUniversity(e.target.value));
  colEl?.addEventListener('change',  (e)=> loadMajorsByCollege(e.target.value));
  btnSearchUsers?.addEventListener('click', searchUsers);

  showBlocks();
})();
</script>
@endsection
