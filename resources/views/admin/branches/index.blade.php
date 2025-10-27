@extends('admin.layouts.app')
@section('title','إدارة الفروع')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">الفروع</h4>
  <a href="{{ route('admin.branches.create') }}" class="btn btn-primary">
    <i class="bi bi-plus"></i> فرع جديد
  </a>
</div>

{{-- فلاتر --}}
<form class="row g-2 mb-3" method="GET">
  <div class="col-md-3">
    <select name="university_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الجامعات —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected(request('university_id') == $u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="بحث بالاسم/العنوان/الهاتف/الإيميل">
  </div>
  <div class="col-md-2">
    <select name="is_active" class="form-select" onchange="this.form.submit()">
      <option value="">— الحالة —</option>
      <option value="1" @selected(request('is_active')==='1')>مفعل</option>
      <option value="0" @selected(request('is_active')==='0')>موقوف</option>
    </select>
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-secondary w-100">بحث</button>
  </div>
  @if(request()->query())
    <div class="col-md-1">
      <a href="{{ route('admin.branches.index') }}" class="btn btn-outline-light text-danger border w-100">مسح</a>
    </div>
  @endif
</form>

<div class="table-responsive">
  <table class="table table-hover align-middle bg-white">
    <thead class="table-light">
      <tr>
        <th style="width:60px">معرّف</th>
        <th>الفرع</th>
        <th>الجامعة</th>
        <th>العنوان</th>
        <th>الهاتف</th>
        <th>الإيميل</th>
        <th>الحالة</th>
        <th class="text-center">إجراءات</th>
      </tr>
    </thead>
    <tbody>
      @forelse($branches as $b)
        <tr>
          <td class="small text-muted">{{ $b->id }}</td>
          <td class="fw-semibold">{{ $b->name }}</td>
          <td class="small text-muted">{{ $b->university?->name ?? '—' }}</td>
          <td class="small text-muted">{{ $b->address ?: '—' }}</td>
          <td class="small">{{ $b->phone ?: '—' }}</td>
          <td class="small">{{ $b->email ?: '—' }}</td>
          <td>
            {!! $b->is_active
              ? '<span class="badge bg-success">مفعل</span>'
              : '<span class="badge bg-secondary">موقوف</span>' !!}
          </td>
          <td class="text-center">
            <a href="{{ route('admin.branches.edit',$b) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
            <form action="{{ route('admin.branches.destroy',$b) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف الفرع؟')">حذف</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="8" class="text-center text-muted">لا توجد بيانات.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $branches->links('vendor.pagination.bootstrap-custom') }}
@endsection
