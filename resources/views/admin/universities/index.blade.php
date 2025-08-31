@extends('admin.layouts.app')
@section('title','الجامعات')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">الجامعات</h4>
  <a href="{{ route('admin.universities.create') }}" class="btn btn-primary">
    <i class="bi bi-plus"></i> جامعة جديدة
  </a>
</div>

<form class="row g-2 mb-3">
  <div class="col-auto">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="بحث بالاسم/الرمز/المنفذ">
  </div>
  <div class="col-auto">
    <button class="btn btn-outline-secondary">بحث</button>
  </div>
</form>

<div class="table-responsive">
  <table class="table table-hover align-middle bg-white">
    <thead class="table-light">
      <tr>
        <th>الشعار</th><th>الاسم</th><th>Slug</th><th>Code</th><th>الألوان</th><th>الحالة</th><th class="text-center">إجراءات</th>
      </tr>
    </thead>
    <tbody>
      @forelse($universities as $u)
      <tr>
        <td>@if($u->logo_url)<img src="{{ $u->logo_url }}" style="height:36px">@endif</td>
        <td>{{ $u->name }}</td>
        <td>{{ $u->slug }}</td>
        <td>{{ $u->code }}</td>
        <td>
          <span class="badge" style="background:{{ $u->primary_color }};">Primary</span>
          <span class="badge" style="background:{{ $u->secondary_color }};">Secondary</span>
        </td>
        <td>{!! $u->is_active ? '<span class="badge bg-success">مفعل</span>' : '<span class="badge bg-secondary">موقوف</span>' !!}</td>
        <td class="text-center">
          <a href="{{ route('admin.universities.edit',$u) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
          <form action="{{ route('admin.universities.destroy',$u) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف الجامعة؟')">حذف</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="7" class="text-center text-muted">لا توجد بيانات.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $universities->links() }}
@endsection
