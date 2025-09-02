@php $isEdit = isset($user); @endphp
<div class="row g-3">

  <div class="col-md-6">
    <label class="form-label">الاسم</label>
    <input type="text" name="name" class="form-control" required value="{{ old('name', $user->name ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">البريد الإلكتروني</label>
    <input type="email" name="email" class="form-control" required value="{{ old('email', $user->email ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label">الرقم الأكاديمي</label>
    <input type="text" name="student_number" class="form-control" value="{{ old('student_number', $user->student_number ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label">رقم الهاتف</label>
    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
  </div>

  <div class="col-md-4">
    <label class="form-label">الصورة الشخصية (اختياري)</label>
    <input type="file" name="profile_photo" class="form-control" accept=".jpg,.jpeg,.png,.webp">
    @if(!empty($user?->profile_photo_url))
      <img src="{{ $user->profile_photo_url }}" class="mt-2 rounded" style="height:48px">
    @endif
  </div>

  <div class="col-md-4">
    <label class="form-label">الجامعة</label>
    <select name="university_id" id="university_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected(old('university_id', $user->university_id ?? '')==$u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">الكلية</label>
    <select name="college_id" id="college_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach($colleges as $c)
        <option value="{{ $c->id }}" @selected(old('college_id', $user->college_id ?? '')==$c->id) data-university="{{ $c->university_id }}">
          {{ $c->name }} ({{ $c->university?->name }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">التخصص</label>
    <select name="major_id" id="major_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach($majors as $m)
        <option value="{{ $m->id }}" @selected(old('major_id', $user->major_id ?? '')==$m->id) data-college="{{ $m->college_id }}">
          {{ $m->name }} ({{ $m->college?->name }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">المستوى</label>
    <input type="number" name="level" class="form-control" min="1" max="20" value="{{ old('level', $user->level ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">الجنس</label>
    @php $g = old('gender', $user->gender ?? ''); @endphp
    <select name="gender" class="form-select">
      <option value="">— اختر —</option>
      <option value="male"   @selected($g==='male')>ذكر</option>
      <option value="female" @selected($g==='female')>أنثى</option>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">الحالة</label>
    @php $st = old('status', $user->status ?? 'active'); @endphp
    <select name="status" class="form-select" required>
      <option value="active"     @selected($st==='active')>نشط</option>
      <option value="suspended"  @selected($st==='suspended')>موقوف</option>
      <option value="graduated"  @selected($st==='graduated')>متخرج</option>
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label">كلمة المرور {{ $isEdit ? '(اتركها فارغة إن لم ترد تغييرها)' : '' }}</label>
    <input type="password" name="password" class="form-control" {{ $isEdit ? '' : 'required' }} minlength="8">
  </div>

</div>

@push('scripts')
<script>
function filterCollegesByUniversity(){
  const uniId = document.getElementById('university_id').value;
  document.querySelectorAll('#college_id option[data-university]').forEach(o => {
    o.hidden = (uniId && o.dataset.university !== uniId);
  });
}
function filterMajorsByCollege(){
  const colId = document.getElementById('college_id').value;
  document.querySelectorAll('#major_id option[data-college]').forEach(o => {
    o.hidden = (colId && o.dataset.college !== colId);
  });
}
document.getElementById('university_id').addEventListener('change', function(){
  filterCollegesByUniversity(); filterMajorsByCollege();
});
document.getElementById('college_id').addEventListener('change', filterMajorsByCollege);

// تهيئة أولية
filterCollegesByUniversity(); filterMajorsByCollege();
</script>
@endpush
