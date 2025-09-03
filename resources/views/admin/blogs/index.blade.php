@extends('admin.layouts.app')
@section('title','المدونة')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">المدونات</h4>
  <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
    <i class="bi bi-plus"></i> تدوينة جديدة
  </a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-3">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="بحث بالعنوان/الملخص/السلَج">
  </div>
  <div class="col-md-3">
    <select name="status" class="form-select" onchange="this.form.submit()">
      <option value="">— الحالة —</option>
      <option value="draft" @selected(request('status')==='draft')>مسودة</option>
      <option value="published" @selected(request('status')==='published')>منشورة</option>
      <option value="archived" @selected(request('status')==='archived')>مؤرشفة</option>
    </select>
  </div>
  <div class="col-md-3">
    <select name="university_id" class="form-select" onchange="this.form.submit()">
      <option value="">— الجامعة —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected(request('university_id')==$u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <select name="doctor_id" class="form-select" onchange="this.form.submit()">
      <option value="">— الدكتور —</option>
      @foreach($doctors as $d)
        <option value="{{ $d->id }}" @selected(request('doctor_id')==$d->id)>{{ $d->name }}</option>
      @endforeach
    </select>
  </div>
</form>

<div class="table-responsive">
<table class="table table-hover bg-white align-middle">
  <thead class="table-light">
    <tr>
      <th>العنوان</th><th>الحالة</th><th>الجامعة</th><th>الدكتور</th><th>تاريخ النشر</th><th class="text-center">إجراءات</th>
    </tr>
  </thead>
  <tbody>
    @forelse($blogs as $b)
    <tr>
      <td class="fw-semibold">{{ $b->title }}</td>
      <td>
        @if($b->status==='published') <span class="badge bg-success">منشورة</span>
        @elseif($b->status==='draft') <span class="badge bg-secondary">مسودة</span>
        @else <span class="badge bg-dark">مؤرشفة</span>
        @endif
      </td>
      <td class="small text-muted">{{ $b->university?->name ?? '—' }}</td>
      <td class="small text-muted">{{ $b->doctor?->name ?? '—' }}</td>
      <td class="small text-muted">{{ $b->published_at?->format('Y-m-d') ?? '—' }}</td>
      <td class="text-center">
        <a href="{{ route('admin.blogs.edit',$b) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
        <form action="{{ route('admin.blogs.destroy',$b) }}" method="POST" class="d-inline">@csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف التدوينة؟')">حذف</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="6" class="text-center text-muted">لا توجد بيانات.</td></tr>
    @endforelse
  </tbody>
</table>
</div>

{{ $blogs->links('vendor.pagination.bootstrap-custom') }}
@endsection
