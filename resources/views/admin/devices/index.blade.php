@extends('admin.layouts.app')
@section('title','الأجهزة/المهام')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">الأجهزة/المهام</h4>
  <a href="{{ route('admin.devices.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> إضافة</a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-5">
    <select name="material_id" class="form-select" onchange="this.form.submit()">
      <option value="">— المادة —</option>
      @foreach($materials as $m)
        <option value="{{ $m->id }}" @selected(request('material_id')==$m->id)>{{ $m->name }} {{ $m->code ? '(' . $m->code . ')' : '' }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="بحث بالاسم">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-secondary w-100">بحث</button>
  </div>
</form>

<div class="table-responsive">
<table class="table table-hover bg-white align-middle">
  <thead class="table-light">
    <tr><th>الاسم</th><th>الكود</th><th>المادة</th><th>الحالة</th><th class="text-center">إجراءات</th></tr>
  </thead>
  <tbody>
    @forelse($devices as $d)
    <tr>
      <td class="fw-semibold">{{ $d->name }}</td>
      <td>{{ $d->code ?: '—' }}</td>
      <td class="small text-muted">{{ $d->material?->name }}</td>
      <td>{!! $d->is_active ? '<span class="badge bg-success">مفعل</span>' : '<span class="badge bg-secondary">موقوف</span>' !!}</td>
      <td class="text-center">
        <a href="{{ route('admin.devices.edit',$d) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
        <form action="{{ route('admin.devices.destroy',$d) }}" method="POST" class="d-inline">@csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف السجل؟')">حذف</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="5" class="text-center text-muted">لا توجد بيانات.</td></tr>
    @endforelse
  </tbody>
</table>
</div>

{{ $devices->links() }}
@endsection
