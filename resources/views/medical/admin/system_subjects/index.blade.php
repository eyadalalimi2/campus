
@extends('admin.layouts.app')
@section('title','ربط جهاز↔مادة')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0"><i class="bi bi-link"></i> ربط جهاز↔مادة</h1>
  <a href="{{ route('medical.system-subjects.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> ربط جديد</a>
</div>
<div class="card mb-3">
  <div class="card-body">
    <form method="get" class="row g-2 align-items-end">
      <div class="col-md-4">
        <label class="form-label">الجهاز</label>
        <select name="system_id" class="form-select">
          <option value="">الكل</option>
          @foreach($systems as $s)
            <option value="{{ $s->id }}" {{ (string)request('system_id')===(string)$s->id?'selected':'' }}>{{ $s->name_ar }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label">المادة</label>
        <select name="subject_id" class="form-select">
          <option value="">الكل</option>
          @foreach($subjects as $subj)
            <option value="{{ $subj->id }}" {{ (string)request('subject_id')===(string)$subj->id?'selected':'' }}>
              {{ $subj->code }} — {{ $subj->name_ar }}
            </option>
          @endforeach
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-outline-secondary w-100"><i class="bi bi-funnel"></i> فلترة</button>
      </div>
    </form>
  </div>
</div>
<div class="card">
  <div class="table-responsive">
    <table class="table table-bordered align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>الجهاز</th>
          <th>المادة</th>
          <th>الفصل</th>
          <th>المستوى</th>
          <th>إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $x)
        <tr>
          <td>{{ $x->system->name_ar }}</td>
          <td>{{ $x->subject->code }} — {{ $x->subject->name_ar }}</td>
          <td>{{ $x->semester_hint }}</td>
          <td>{{ $x->level }}</td>
          <td>
            <a href="{{ route('medical.system-subjects.edit',$x) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> تعديل</a>
            <form action="{{ route('medical.system-subjects.destroy',$x) }}" method="post" class="d-inline">@csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')"><i class="bi bi-trash"></i> حذف</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">لا توجد روابط</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">{{ $items->links('vendor.pagination.bootstrap-custom') }}</div>
</div>
@endsection
