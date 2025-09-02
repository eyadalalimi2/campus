@extends('admin.layouts.app')
@section('title','التخصصات')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">التخصصات</h4>
  <a href="{{ route('admin.majors.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> تخصص جديد</a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-4">
    <select name="university_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الجامعات —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected(request('university_id')==$u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <select name="college_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الكليات —</option>
      @foreach($colleges as $c)
        <option value="{{ $c->id }}" @selected(request('college_id')==$c->id)>{{ $c->name }} ({{ $c->university->name }})</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="بحث بالاسم">
  </div>
  <div class="col-md-1">
    <button class="btn btn-outline-secondary w-100">بحث</button>
  </div>
</form>

<div class="table-responsive">
  <table class="table table-hover bg-white">
    <thead class="table-light">
      <tr><th>التخصص</th><th>الكلية</th><th>الجامعة</th><th>الحالة</th><th class="text-center">إجراءات</th></tr>
    </thead>
    <tbody>
      @forelse($majors as $m)
      <tr>
        <td>{{ $m->name }}</td>
        <td>{{ $m->college->name }}</td>
        <td>{{ $m->college->university->name }}</td>
        <td>{!! $m->is_active ? '<span class="badge bg-success">مفعل</span>' : '<span class="badge bg-secondary">موقوف</span>' !!}</td>
        <td class="text-center">
          <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.majors.edit',$m) }}">تعديل</a>
          <form action="{{ route('admin.majors.destroy',$m) }}" method="POST" class="d-inline">@csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف التخصص؟')">حذف</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="text-center text-muted">لا توجد بيانات.</td></tr>
      @endforelse
    </tbody>
  </table>
  

</div>

{{ $majors->links('vendor.pagination.bootstrap-custom') }}
@endsection
