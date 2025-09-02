@extends('admin.layouts.app')
@section('title','المواد')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">المواد</h4>
  <a href="{{ route('admin.materials.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> مادة جديدة</a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-2">
    <select name="scope" class="form-select" onchange="this.form.submit()">
      <option value="">النطاق</option>
      <option value="global" @selected(request('scope')==='global')>عام</option>
      <option value="university" @selected(request('scope')==='university')>خاص</option>
    </select>
  </div>
  <div class="col-md-2">
    <select name="university_id" class="form-select" onchange="this.form.submit()">
      <option value="">الجامعة</option>
      @foreach($universities as $u)
      <option value="{{ $u->id }}" @selected(request('university_id')==$u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <select name="college_id" class="form-select" onchange="this.form.submit()">
      <option value="">الكلية</option>
      @foreach($colleges as $c)
      <option value="{{ $c->id }}" @selected(request('college_id')==$c->id)>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <select name="major_id" class="form-select" onchange="this.form.submit()">
      <option value="">التخصص</option>
      @foreach($majors as $m)
      <option value="{{ $m->id }}" @selected(request('major_id')==$m->id)>{{ $m->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <input type="number" name="level" class="form-control" value="{{ request('level') }}" placeholder="المستوى">
  </div>
  <div class="col-md-2">
    <select name="term" class="form-select" onchange="this.form.submit()">
      <option value="">الفترة</option>
      <option value="first"  @selected(request('term')==='first')>الأول</option>
      <option value="second" @selected(request('term')==='second')>الثاني</option>
      <option value="summer" @selected(request('term')==='summer')>الصيفي</option>
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
    <tr>
      <th>الاسم</th>
      <th>النطاق</th>
      <th>الجامعة/الكلية/التخصص</th>
      <th>المستوى/الفترة</th>
      <th>الحالة</th>
      <th class="text-center">إجراءات</th>
    </tr>
  </thead>
  <tbody>
    @forelse($materials as $m)
    <tr>
      <td class="fw-semibold">{{ $m->name }}</td>
      <td>{!! $m->scope==='global' ? '<span class="badge bg-success">عام</span>' : '<span class="badge bg-primary">خاص</span>' !!}</td>
      <td class="small text-muted">
        @if($m->scope==='university')
          {{ optional($m->university)->name ?? '—' }}
          @if($m->college) / {{ $m->college->name }} @endif
          @if($m->major)   / {{ $m->major->name }} @endif
        @else — @endif
      </td>
      <td>{{ $m->level ?: '—' }} / {{ $m->term ? ['first'=>'الأول','second'=>'الثاني','summer'=>'الصيفي'][$m->term] : '—' }}</td>
      <td>{!! $m->is_active ? '<span class="badge bg-success">مفعل</span>' : '<span class="badge bg-secondary">موقوف</span>' !!}</td>
      <td class="text-center">
        <a href="{{ route('admin.materials.edit',$m) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
        <form action="{{ route('admin.materials.destroy',$m) }}" method="POST" class="d-inline">@csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف المادة؟')">حذف</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="6" class="text-center text-muted">لا توجد بيانات.</td></tr>
    @endforelse
  </tbody>
</table>
</div>
{{ $materials->links() }}
@endsection
