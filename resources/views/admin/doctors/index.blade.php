@extends('admin.layouts.app')
@section('title','الدكاترة')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">الدكاترة</h4>
  <a href="{{ route('admin.doctors.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> إضافة دكتور</a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-2">
    <select name="type" class="form-select" onchange="this.form.submit()">
      <option value="">— الكل (النوع) —</option>
      <option value="university" @selected(request('type')==='university')>جامعي</option>
      <option value="independent" @selected(request('type')==='independent')>مستقل</option>
    </select>
  </div>
  <div class="col-md-3">
    <select name="university_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الجامعات —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected(request('university_id')==$u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <select name="college_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الكليات —</option>
      @foreach($colleges as $c)
        <option value="{{ $c->id }}" @selected(request('college_id')==$c->id)>{{ $c->name }} ({{ $c->university->name }})</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <select name="major_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل التخصصات —</option>
      @foreach($majors as $m)
        <option value="{{ $m->id }}" @selected(request('major_id')==$m->id)>{{ $m->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-1">
    <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="بحث">
  </div>
</form>

<div class="table-responsive">
  <table class="table table-hover bg-white align-middle">
    <thead class="table-light">
      <tr>
        <th>الصورة</th><th>الاسم</th><th>النوع</th><th>الانتماء</th><th>التخصصات</th><th>الهاتف</th><th>الحالة</th><th class="text-center">إجراءات</th>
      </tr>
    </thead>
    <tbody>
      @forelse($doctors as $d)
      <tr>
        <td>@if($d->photo_url)<img src="{{ $d->photo_url }}" style="height:40px;border-radius:8px">@endif</td>
        <td>{{ $d->name }}</td>
        <td>{!! $d->type==='university' ? '<span class="badge bg-primary">جامعي</span>' : '<span class="badge bg-info text-dark">مستقل</span>' !!}</td>
        <td>
          @if($d->type==='university')
            <div class="small text-muted">
              {{ optional($d->university)->name }} / {{ optional($d->college)->name }} / {{ optional($d->major)->name }}
            </div>
          @else
            <span class="text-muted small">—</span>
          @endif
        </td>
        <td>
          @if($d->type==='university')
            <span class="badge bg-light text-dark">{{ optional($d->major)->name }}</span>
          @else
            @foreach($d->majors as $m)
              <span class="badge bg-light text-dark">{{ $m->name }}</span>
            @endforeach
          @endif
        </td>
        <td>{{ $d->phone }}</td>
        <td>{!! $d->is_active ? '<span class="badge bg-success">مفعل</span>' : '<span class="badge bg-secondary">موقوف</span>' !!}</td>
        <td class="text-center">
          <a href="{{ route('admin.doctors.edit',$d) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
          <form action="{{ route('admin.doctors.destroy',$d) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف الدكتور؟')">حذف</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="8" class="text-center text-muted">لا توجد بيانات.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $doctors->links('vendor.pagination.bootstrap-custom') }}
@endsection
