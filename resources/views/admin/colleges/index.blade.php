@extends('admin.layouts.app')
@section('title','الكليات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">الكليات</h4>
  <a href="{{ route('admin.colleges.create') }}" class="btn btn-primary">
    <i class="bi bi-plus"></i> كلية جديدة
  </a>
</div>

<form class="row g-2 mb-3">
  {{-- فلترة حسب الجامعة --}}
  <div class="col-md-4">
    <label class="form-label">الجامعة</label>
    <select name="university_id" id="f_university_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الجامعات —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected(request('university_id') == $u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- فلترة حسب الفرع (يُرشّح حسب الجامعة المختارة) --}}
  <div class="col-md-4">
    <label class="form-label">الفرع</label>
    <select name="branch_id" id="f_branch_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الفروع —</option>
      @foreach($branches as $b)
        <option value="{{ $b->id }}"
                data-university="{{ $b->university_id }}"
                @selected(request('branch_id') == $b->id)>
          {{ $b->name }} — {{ optional($b->university)->name }}
        </option>
      @endforeach
    </select>
  </div>

  {{-- حقل البحث --}}
  <div class="col-md-3">
    <label class="form-label">بحث</label>
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="اسم الكلية">
  </div>
  <div class="col-md-1 d-flex align-items-end">
    <button class="btn btn-outline-secondary w-100">بحث</button>
  </div>
</form>

<div class="table-responsive">
  <table class="table table-hover bg-white align-middle">
    <thead class="table-light">
      <tr>
        <th>الكلية</th>
        <th>الفرع</th>
        <th>الجامعة</th>
        <th>الحالة</th>
        <th class="text-center">إجراءات</th>
      </tr>
    </thead>
    <tbody>
      @forelse($colleges as $c)
        <tr>
          <td class="fw-semibold">{{ $c->name }}</td>
          <td class="text-muted">{{ optional($c->branch)->name ?? '—' }}</td>
          <td class="text-muted">{{ optional($c->branch?->university)->name ?? '—' }}</td>
          <td>
            @if($c->is_active)
              <span class="badge bg-success">مفعل</span>
            @else
              <span class="badge bg-secondary">موقوف</span>
            @endif
          </td>
          <td class="text-center">
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.colleges.edit',$c) }}">تعديل</a>
            <form action="{{ route('admin.colleges.destroy',$c) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف الكلية؟')">حذف</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center text-muted">لا توجد بيانات.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $colleges->links('vendor.pagination.bootstrap-custom') }}
@endsection

@push('scripts')
<script>
(function(){
  // ترشيح قائمة الفروع في الفلاتر بحسب الجامعة المختارة
  const uniSel = document.getElementById('f_university_id');
  const brSel  = document.getElementById('f_branch_id');
  if(!uniSel || !brSel) return;

  const allOpts = Array.from(brSel.querySelectorAll('option')).map(o => ({el:o, uni:o.getAttribute('data-university')}));
  function filter(){
    const uid = uniSel.value || '';
    const first = allOpts.find(x => x.el.value === '');
    brSel.innerHTML = '';
    if(first) brSel.appendChild(first.el);
    allOpts.forEach(({el,uni})=>{
      if(!el.value) return;
      if(!uid || uni === uid) brSel.appendChild(el);
    });
    // لوالفرع المحدد لا يخص الجامعة المختارة، صفّره
    const sel = brSel.options[brSel.selectedIndex];
    if(sel && sel.getAttribute && uid && sel.getAttribute('data-university') !== uid){
      brSel.value = '';
    }
  }
  uniSel.addEventListener('change', filter);
  filter();
})();
</script>
@endpush
