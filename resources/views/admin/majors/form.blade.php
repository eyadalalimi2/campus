<div class="row g-3">
  {{-- الجامعة (للتصفية فقط) --}}
  <div class="col-md-6">
    <label class="form-label">الجامعة</label>
    <select class="form-select" id="university_select" onchange="filterColleges()">
      <option value="">— اختر —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected(old('university_id') == $u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
    <div class="form-text">لا يتم حفظ هذا الحقل، يستخدم فقط لتصفية الكليات أدناه.</div>
  </div>

  {{-- الكلية (المطلوبة للحفظ) --}}
  <div class="col-md-6">
    <label class="form-label">الكلية <span class="text-danger">*</span></label>
    <select name="college_id" class="form-select" id="college_select" required>
      <option value="">— اختر —</option>
      @foreach($colleges as $c)
        <option
          value="{{ $c->id }}"
          data-university="{{ $c->university_id }}"
          @selected(old('college_id', $major->college_id ?? '') == $c->id)
        >
          {{ $c->name }} ({{ $c->university->name }})
        </option>
      @endforeach
    </select>
  </div>

  {{-- اسم التخصص --}}
  <div class="col-md-8">
    <label class="form-label">اسم التخصص <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required value="{{ old('name', $major->name ?? '') }}">
  </div>

  {{-- الحالة --}}
  <div class="col-md-4 d-flex align-items-end">
    <div class="form-check">
      {{-- لضمان إرسال 0 عند إلغاء التحديد --}}
      <input type="hidden" name="is_active" value="0">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="m_active"
             {{ old('is_active', $major->is_active ?? true) ? 'checked' : '' }}>
      <label class="form-check-label" for="m_active">مفعل</label>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function(){
  const uniSelect = document.getElementById('university_select');
  const collegeSelect = document.getElementById('college_select');

  // إخفاء/إظهار الكليات حسب الجامعة المختارة
  window.filterColleges = function(){
    const uni = uniSelect.value;
    const opts = collegeSelect.querySelectorAll('option[data-university]');
    opts.forEach(o => {
      const match = !uni || o.dataset.university === uni;
      o.hidden = !match;
      // لو الخيار الحالي مخفي، أزل اختياره
      if (!match && o.selected) o.selected = false;
    });
  };

  // عند التحميل: لو الكلية محددة سلفًا، اضبط الجامعة تلقائيًا لتلك الكلية
  document.addEventListener('DOMContentLoaded', function(){
    const selectedCollege = collegeSelect.querySelector('option[data-university][selected]');
    if (selectedCollege && !uniSelect.value) {
      uniSelect.value = selectedCollege.dataset.university;
    }
    filterColleges();
  });
})();
</script>
@endpush
