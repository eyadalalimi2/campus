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

  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">الجامعة</label>
      <select name="university_id" id="reg_uni" class="form-select">
        <option value="">— اختر —</option>
        @foreach(\App\Models\University::orderBy('name')->get() as $u)
          <option value="{{ $u->id }}" @selected(old('university_id')==$u->id)>{{ $u->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">الكلية</label>
      <select name="college_id" id="reg_college" class="form-select">
        <option value="">— اختر —</option>
        @foreach(\App\Models\College::orderBy('name')->get() as $c)
          <option value="{{ $c->id }}" @selected(old('college_id')==$c->id) data-university="{{ $c->university_id }}">{{ $c->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">التخصص</label>
      <select name="major_id" id="reg_major" class="form-select">
        <option value="">— اختر —</option>
        @foreach(\App\Models\Major::orderBy('name')->get() as $m)
          <option value="{{ $m->id }}" @selected(old('major_id')==$m->id) data-college="{{ $m->college_id }}">{{ $m->name }}</option>
        @endforeach
      </select>
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
  const uni   = document.getElementById('reg_uni');
  const col   = document.getElementById('reg_college');
  const major = document.getElementById('reg_major');

  function filterColleges(){
    const uid = uni.value;
    [...col.options].forEach(o=>{
      if(!o.value) return;
      o.hidden = (uid && o.dataset.university !== uid);
      if(o.hidden && o.selected) col.value='';
    });
    filterMajors();
  }
  function filterMajors(){
    const cid = col.value;
    [...major.options].forEach(o=>{
      if(!o.value) return;
      o.hidden = (cid && o.dataset.college !== cid);
      if(o.hidden && o.selected) major.value='';
    });
  }
  uni.addEventListener('change', filterColleges);
  col.addEventListener('change', filterMajors);
  filterColleges();
</script>
@endpush
@endsection
