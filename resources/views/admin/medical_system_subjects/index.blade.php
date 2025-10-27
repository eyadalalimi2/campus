@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1 class="mb-3">ربط الأنظمة بمواد المسار SYSTEM</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <div class="card mb-3">
    <div class="card-body">
      <form method="get" class="row g-2">
        <div class="col-md-8">
          <label class="form-label">اختر النظام</label>
          <select name="system_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- اختر --</option>
            @foreach($systems as $s)
              <option value="{{ $s->id }}" @selected($systemId==$s->id)>
                #{{ $s->id }} - {{ optional($s->year->major)->name }} / سنة {{ optional($s->year)->year_number }} / ترم {{ optional($s->term)->term_number }} - {{ $s->display_name ?? optional($s->device)->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button class="btn btn-outline-secondary w-100" type="submit">تحديث</button>
        </div>
      </form>
    </div>
  </div>

  @if($systemId)
    <div class="card mb-3">
      <div class="card-header">الروابط الحالية</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>المادة العامة</th>
                <th>السنة/الترم</th>
                <th>المسار</th>
                <th class="text-end">إجراءات</th>
              </tr>
            </thead>
            <tbody>
              @forelse($links as $lnk)
                <tr>
                  <td>{{ $lnk->id }}</td>
                  <td>{{ optional($lnk->subject->medSubject)->name }}</td>
                  <td>
                    سنة {{ optional($lnk->subject->term->year)->year_number }}
                    / ترم {{ optional($lnk->subject->term)->term_number }}
                  </td>
                  <td>{{ $lnk->subject->track }}</td>
                  <td class="text-end">
                    <form method="post" action="{{ route('admin.medical_system_subjects.destroy',$lnk) }}" onsubmit="return confirm('حذف الربط؟')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">حذف</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center text-muted">لا توجد روابط</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    
  @endif
    <!-- قائمة المواد: بطاقة للاختيار المتعدد -->
    @if($systemId)
      <div class="card mt-3">
        <div class="card-header">قائمة المواد (اختر واحداً أو أكثر)</div>
        <div class="card-body">
          <form method="post" action="{{ route('admin.medical_system_subjects.store') }}">
            @csrf
            <input type="hidden" name="system_id" value="{{ $systemId }}">

            <div class="row g-2 mb-3">
              <div class="col-md-6">
                <input id="subjectSearch" type="text" class="form-control" placeholder="ابحث عن مادة بالاسم أو ID">
              </div>
              <div class="col-md-6 d-flex justify-content-end align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="selectAll">
                  <label class="form-check-label" for="selectAll">تحديد الكل</label>
                </div>
              </div>
            </div>

            <div class="table-responsive" style="max-height:420px; overflow:auto;">
              <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width:40px"></th>
                    <th>ID</th>
                    <th>المادة العامة</th>
                    <th>السنة/الترم</th>
                    <th>المسار</th>
                  </tr>
                </thead>
                <tbody id="subjectsTable">
                  @forelse($subjects ?? [] as $sub)
                    <tr>
                      <td class="align-middle"><input type="checkbox" name="subject_ids[]" value="{{ $sub->id }}"></td>
                      <td class="align-middle">{{ $sub->id }}</td>
                      <td class="align-middle">{{ optional($sub->medSubject)->name }}</td>
                      <td class="align-middle">سنة {{ optional($sub->term->year)->year_number }} / ترم {{ optional($sub->term)->term_number }}</td>
                      <td class="align-middle">{{ $sub->track }}</td>
                    </tr>
                  @empty
                    <tr><td colspan="5" class="text-center text-muted">لا توجد مواد</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            @error('subject_ids')<div class="text-danger small mt-2">{{ $message }}</div>@enderror

            <div class="mt-3">
              <button class="btn btn-primary">إضافة المواد المحددة</button>
            </div>
          </form>
        </div>
      </div>
    @endif
</div>
@push('scripts')
<script>
  (function(){
    const search = document.getElementById('subjectSearch');
    const table = document.getElementById('subjectsTable');
    const selectAll = document.getElementById('selectAll');

    if (search) {
      search.addEventListener('input', function(){
        const q = this.value.trim().toLowerCase();
        Array.from(table.querySelectorAll('tr')).forEach(function(tr){
          const text = tr.innerText.toLowerCase();
          tr.style.display = q === '' || text.indexOf(q) !== -1 ? '' : 'none';
        });
      });
    }

    if (selectAll) {
      selectAll.addEventListener('change', function(){
        const checked = this.checked;
        Array.from(table.querySelectorAll('input[type="checkbox"][name="subject_ids[]"]')).forEach(function(cb){
          if (cb.closest('tr').style.display !== 'none') cb.checked = checked;
        });
      });
    }
  })();
</script>
@endpush

@endsection