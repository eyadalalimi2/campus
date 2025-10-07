@php
  $currentUniversityId = old('university_id', optional($college?->branch?->university)->id ?? request('university_id'));
  $currentBranchId     = old('branch_id', $college->branch_id ?? request('branch_id'));
@endphp

<div class="row g-3">
  {{-- الجامعة (للاختيار فقط، لا يتم إرسالها) --}}
  <div class="col-md-6">
    <label class="form-label">الجامعة <span class="text-danger">*</span></label>
  <select name="university_id" id="university_id" class="form-select" required>
      <option value="">— اختر —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected($currentUniversityId == $u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- الفرع --}}
  <div class="col-md-6">
    <label class="form-label">الفرع <span class="text-danger">*</span></label>
    <select name="branch_id" id="branch_id" class="form-select" required>
      <option value="">— اختر الجامعة أولاً —</option>
      @foreach($branches as $b)
        <option value="{{ $b->id }}"
                data-university="{{ $b->university_id }}"
                @selected($currentBranchId == $b->id)>
          {{ $b->name }}
        </option>
      @endforeach
    </select>
    @error('branch_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
  </div>

  {{-- اسم الكلية --}}
  <div class="col-md-8">
    <label class="form-label">اسم الكلية <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required
           value="{{ old('name', $college->name ?? '') }}">
    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
  </div>

  {{-- الحالة --}}
  <div class="col-md-4 d-flex align-items-end">
    <div class="form-check">
      <input type="hidden" name="is_active" value="0">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="c_active"
             {{ old('is_active', $college->is_active ?? 1) ? 'checked' : '' }}>
      <label class="form-check-label" for="c_active">مفعل</label>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function(){
  const uniSel    = document.getElementById('university_id');
  const branchSel = document.getElementById('branch_id');
  if(!uniSel || !branchSel) return;

  const allBranchOptions = Array.from(branchSel.querySelectorAll('option'))
    .map(o => ({el:o, uni: o.getAttribute('data-university')}));

  function filterBranches(){
    const uid = uniSel.value || '';
    // أبق الخيار الأول
    const first = branchSel.querySelector('option[value=""]');
    branchSel.innerHTML = '';
    if(first) branchSel.appendChild(first);

    allBranchOptions.forEach(({el, uni}) => {
      if(!el.value) return; // تخطّي الفارغ
      if(!uid || uni === uid){
        branchSel.appendChild(el);
      }
    });

    // إن كانت القيمة الحالية لا تنتمي للجامعة المختارة، صفّرها
    if (branchSel.selectedIndex <= 0) {
      branchSel.value = '';
    } else {
      const selected = branchSel.options[branchSel.selectedIndex];
      if (selected && selected.getAttribute('data-university') !== uid) {
        branchSel.value = '';
      }
    }
  }

  uniSel.addEventListener('change', filterBranches);
  // تفعيل أولي
  filterBranches();
})();
</script>
@endpush
