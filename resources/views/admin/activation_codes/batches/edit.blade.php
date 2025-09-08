@extends('admin.layouts.app')
@section('title','تعديل الدفعة')

@section('content')
<h4 class="mb-3">تعديل الدفعة: {{ $batch->display_name }}</h4>

<form action="{{ route('admin.activation_code_batches.update', $batch) }}" method="POST" class="card p-3">
  @csrf @method('PUT')

  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">اسم الدفعة</label>
      <input type="text" name="name" class="form-control" value="{{ old('name',$batch->name) }}" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">الخطة</label>
      <select name="plan_id" class="form-select" required>
        @foreach($plans as $p)
          <option value="{{ $p->id }}" @selected(old('plan_id',$batch->plan_id)==$p->id)>{{ $p->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">الحالة</label>
      <select name="status" class="form-select">
        @foreach(['draft','active','disabled','archived'] as $st)
          <option value="{{ $st }}" @selected(old('status',$batch->status)===$st)>{{ $st }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-12"><hr><strong>النطاق</strong></div>
    <div class="col-md-4">
      <label class="form-label">الجامعة</label>
      <select name="university_id" id="university_select" class="form-select">
        <option value="">— اختر —</option>
        @foreach($universities as $u)
          <option value="{{ $u->id }}" @selected(old('university_id',$batch->university_id)==$u->id)>{{ $u->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">الكلية</label>
      <select name="college_id" id="college_select" class="form-select">
        <option value="">— اختر —</option>
        @foreach($colleges as $c)
          <option value="{{ $c->id }}" data-university="{{ $c->university_id }}" @selected(old('college_id',$batch->college_id)==$c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">التخصص</label>
      <select name="major_id" id="major_select" class="form-select">
        <option value="">— اختر —</option>
        @foreach($majors as $m)
          <option value="{{ $m->id }}" data-college="{{ $m->college_id }}" @selected(old('major_id',$batch->major_id)==$m->id)>{{ $m->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-12"><hr><strong>السياسة والصلاحية</strong></div>
    <div class="col-md-3">
      <label class="form-label">المدة (يوم)</label>
      <input type="number" name="duration_days" class="form-control" min="1" max="1825" value="{{ old('duration_days',$batch->duration_days) }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">سياسة البداية</label>
      <select name="start_policy" id="start_policy" class="form-select" required>
        <option value="on_redeem" @selected(old('start_policy',$batch->start_policy)==='on_redeem')>تبدأ عند التفعيل</option>
        <option value="fixed_start" @selected(old('start_policy',$batch->start_policy)==='fixed_start')>تاريخ ثابت</option>
      </select>
    </div>
    <div class="col-md-3 start-on">
      <label class="form-label">تبدأ في</label>
      <input type="date" name="starts_on" class="form-control" value="{{ old('starts_on', optional($batch->starts_on)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">صالح من</label>
      <input type="datetime-local" name="valid_from" class="form-control" value="{{ old('valid_from', optional($batch->valid_from)->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">صالح حتى</label>
      <input type="datetime-local" name="valid_until" class="form-control" value="{{ old('valid_until', optional($batch->valid_until)->format('Y-m-d\TH:i')) }}">
    </div>

    <div class="col-12"><hr><strong>خيارات الكود</strong></div>
    <div class="col-md-3">
      <label class="form-label">بادئة</label>
      <input type="text" name="code_prefix" class="form-control" value="{{ old('code_prefix',$batch->code_prefix) }}" maxlength="24">
    </div>
    <div class="col-md-3">
      <label class="form-label">الطول</label>
      <input type="number" name="code_length" class="form-control" min="4" max="24" value="{{ old('code_length',$batch->code_length) }}">
    </div>

    <div class="col-12">
      <label class="form-label">ملاحظات</label>
      <textarea name="notes" class="form-control" rows="3">{{ old('notes',$batch->notes) }}</textarea>
    </div>
  </div>

  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.activation_code_batches.show',$batch) }}" class="btn btn-link">رجوع</a>
  </div>
</form>

@push('scripts')
<script>
function cascade(){
  const uni = document.getElementById('university_select')?.value || '';
  const col = document.getElementById('college_select');
  const maj = document.getElementById('major_select');

  if(col){
    [...col.options].forEach(o=>{
      if(!o.value) return;
      const show = !uni || (o.dataset.university === uni);
      o.hidden = !show; if(!show && o.selected) o.selected = false;
    });
  }
  const colVal = col?.value || '';
  if(maj){
    [...maj.options].forEach(o=>{
      if(!o.value) return;
      const show = !colVal || (o.dataset.college === colVal);
      o.hidden = !show; if(!show && o.selected) o.selected = false;
    });
  }
}
function toggleStart(){
  const sp = document.getElementById('start_policy').value;
  document.querySelectorAll('.start-on').forEach(el=> el.style.display = (sp==='fixed_start' ? '' : 'none'));
}
document.getElementById('university_select').addEventListener('change', cascade);
document.getElementById('college_select').addEventListener('change', cascade);
document.getElementById('start_policy').addEventListener('change', toggleStart);
cascade(); toggleStart();
</script>
@endpush
@endsection
