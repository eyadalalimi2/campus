@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1 class="mb-3">ربط محتوى (contents) بمواد الطب الخاصة</h1>

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
        <div class="col-lg-9">
          <label class="form-label">اختر المادة الخاصة</label>
          <select name="subject_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- اختر --</option>
            @foreach($subjects as $s)
              <option value="{{ $s->id }}" @selected($subjectId==$s->id)>
                #{{ $s->id }} - {{ optional($s->medSubject)->name }}
                (سنة {{ optional($s->term->year)->year_number }} / ترم {{ optional($s->term)->term_number }} / {{ $s->track }})
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-lg-3 d-flex align-items-end">
          <button class="btn btn-outline-secondary w-100" type="submit">تحديث</button>
        </div>
      </form>
    </div>
  </div>

  @if($subjectId)
    <div class="card mb-3">
      <div class="card-header">الروابط الحالية</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>المحتوى</th>
                <th>النوع</th>
                <th>Primary</th>
                <th>الترتيب</th>
                <th>ملاحظات</th>
                <th class="text-end">إجراءات</th>
              </tr>
            </thead>
            <tbody>
              @forelse($links as $lnk)
                <tr>
                  <td>{{ $lnk->id }}</td>
                  <td>#{{ $lnk->content_id }} - {{ optional($lnk->content)->title }}</td>
                  <td>{{ optional($lnk->content)->type }}</td>
                  <td>{!! $lnk->is_primary ? '<span class="badge bg-info">Primary</span>' : '—' !!}</td>
                  <td>{{ $lnk->sort_order }}</td>
                  <td>{{ $lnk->notes }}</td>
                  <td class="text-end">
                    <form method="post" action="{{ route('admin.medical_subject_contents.destroy',$lnk) }}" onsubmit="return confirm('حذف الربط؟')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">حذف</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="7" class="text-center text-muted">لا توجد روابط</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- بطاقة اختيار متعدد للمحتويات -->
    @if($subjectId)
      <div class="card mt-3">
        <div class="card-header">قائمة المحتويات (اختر واحداً أو أكثر)</div>
        <div class="card-body">
          <form method="post" action="{{ route('admin.medical_subject_contents.store') }}">
            @csrf
            <input type="hidden" name="subject_id" value="{{ $subjectId }}">

            <div class="row g-2 mb-3">
              <div class="col-md-6">
                <input id="contentSearch" type="text" class="form-control" placeholder="ابحث عن محتوى بالعنوان أو ID">
              </div>
              <div class="col-md-6 d-flex justify-content-end align-items-center">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="selectAllContents">
                  <label class="form-check-label" for="selectAllContents">تحديد الكل</label>
                </div>
              </div>
            </div>

            <div class="table-responsive" style="max-height:420px; overflow:auto;">
              <table class="table table-bordered table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width:40px"></th>
                    <th>ID</th>
                    <th>العنوان</th>
                    <th>النوع</th>
                    <th>تاريخ النشر</th>
                  </tr>
                </thead>
                <tbody id="contentsTable">
                  @forelse($contents ?? [] as $c)
                    <tr>
                      <td class="align-middle"><input type="checkbox" name="content_ids[]" value="{{ $c->id }}"></td>
                      <td class="align-middle">{{ $c->id }}</td>
                      <td class="align-middle">{{ $c->title }}</td>
                      <td class="align-middle">{{ $c->type }}</td>
                      <td class="align-middle">{{ optional($c->published_at)->format('Y-m-d') }}</td>
                    </tr>
                  @empty
                    <tr><td colspan="5" class="text-center text-muted">لا توجد محتويات قابلة للربط</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            <div class="row g-3 mt-3">
              <div class="col-md-3">
                <label class="form-label">الترتيب (افتراضي على جميع المحدد)</label>
                <input name="sort_order" type="number" min="0" class="form-control" value="{{ old('sort_order',0) }}">
              </div>
              <div class="col-md-2 d-flex align-items-end">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="is_primary" value="1" id="is_primary_cnt" @checked(old('is_primary'))>
                  <label class="form-check-label" for="is_primary_cnt">Primary</label>
                </div>
              </div>
              <div class="col-12">
                <label class="form-label">ملاحظات (تنطبق على جميع المحدد)</label>
                <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
              </div>
            </div>

            @error('content_ids')<div class="text-danger small mt-2">{{ $message }}</div>@enderror

            <div class="mt-3">
              <button class="btn btn-primary">إضافة المحتويات المحددة</button>
            </div>
          </form>
        </div>
      </div>
    @endif
  @endif
</div>
@push('scripts')
<script>
  (function(){
    const search = document.getElementById('contentSearch');
    const table = document.getElementById('contentsTable');
    const selectAll = document.getElementById('selectAllContents');

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
        Array.from(table.querySelectorAll('input[type="checkbox"][name="content_ids[]"]')).forEach(function(cb){
          if (cb.closest('tr').style.display !== 'none') cb.checked = checked;
        });
      });
    }
  })();
</script>
@endpush
@endsection