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

    <div class="card">
      <div class="card-header">إضافة ربط جديد</div>
      <div class="card-body">
        <form method="post" action="{{ route('admin.medical_subject_contents.store') }}" class="row g-3">
          @csrf
          <input type="hidden" name="subject_id" value="{{ $subjectId }}">

          <div class="col-md-4">
            <label class="form-label">ID المحتوى</label>
            <input name="content_id" class="form-control" placeholder="ID لمحتوى Published + Active + File/Link">
            @error('content_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-3">
            <label class="form-label">الترتيب</label>
            <input name="sort_order" type="number" min="0" class="form-control" value="{{ old('sort_order',0) }}">
          </div>

          <div class="col-md-2 d-flex align-items-end">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="is_primary" value="1" id="is_primary_cnt" @checked(old('is_primary'))>
              <label class="form-check-label" for="is_primary_cnt">Primary</label>
            </div>
          </div>

          <div class="col-12">
            <label class="form-label">ملاحظات</label>
            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
          </div>

          <div class="col-12">
            <button class="btn btn-primary">إضافة</button>
          </div>
        </form>
      </div>
    </div>
  @endif
</div>
@endsection