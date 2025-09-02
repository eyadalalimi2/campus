@extends('admin.layouts.app')
@section('title','إدارة المحتوى')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">المحتوى</h4>
  <a href="{{ route('admin.contents.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> إضافة محتوى</a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-2">
    <select name="scope" class="form-select" onchange="this.form.submit()">
      <option value="">— كل النطاقات —</option>
      <option value="global" @selected(request('scope')==='global')>عام</option>
      <option value="university" @selected(request('scope')==='university')>خاص بجامعة</option>
    </select>
  </div>
  <div class="col-md-2">
    <select name="type" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الأنواع —</option>
      <option value="file" @selected(request('type')==='file')>ملف</option>
      <option value="video" @selected(request('type')==='video')>فيديو</option>
      <option value="link" @selected(request('type')==='link')>رابط</option>
    </select>
  </div>
  <div class="col-md-2">
    <select name="is_active" class="form-select" onchange="this.form.submit()">
      <option value="">— الحالة —</option>
      <option value="1" @selected(request('is_active')==='1')>مفعل</option>
      <option value="0" @selected(request('is_active')==='0')>موقوف</option>
    </select>
  </div>
  <div class="col-md-2">
    <select name="university_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الجامعات —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected(request('university_id')==$u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <select name="college_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الكليات —</option>
      @foreach($colleges as $c)
        <option value="{{ $c->id }}" @selected(request('college_id')==$c->id)>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <select name="major_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل التخصصات —</option>
      @foreach($majors as $m)
        <option value="{{ $m->id }}" @selected(request('major_id')==$m->id)>{{ $m->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <select name="doctor_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الدكاترة —</option>
      @foreach($doctors as $d)
        <option value="{{ $d->id }}" @selected(request('doctor_id')==$d->id)>{{ $d->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="بحث بالعنوان/الوصف">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-secondary w-100">بحث</button>
  </div>
</form>

<div class="table-responsive">
<table class="table table-hover bg-white align-middle">
  <thead class="table-light">
    <tr>
      <th>العنوان</th>
      <th>النوع</th>
      <th>النطاق</th>
      <th>الجامعة/الكلية/التخصص</th>
      <th>الدكتور</th>
      <th>المصدر/الملف</th>
      <th>الحالة</th>
      <th class="text-center">إجراءات</th>
    </tr>
  </thead>
  <tbody>
    @forelse($contents as $c)
    <tr>
      <td class="fw-semibold">{{ $c->title }}</td>
      <td>
        @if($c->type==='file') <span class="badge bg-secondary">ملف</span>
        @elseif($c->type==='video') <span class="badge bg-info text-dark">فيديو</span>
        @else <span class="badge bg-light text-dark">رابط</span>
        @endif
      </td>
      <td>
        {!! $c->scope==='global'
          ? '<span class="badge bg-success">عام</span>'
          : '<span class="badge bg-primary">خاص</span>' !!}
      </td>
      <td class="small text-muted">
        @if($c->scope==='university')
          {{ optional($c->university)->name ?? '—' }}
          @if($c->college) / {{ $c->college->name }} @endif
          @if($c->major) / {{ $c->major->name }} @endif
        @else
          —
        @endif
      </td>
      <td class="small">{{ optional($c->doctor)->name ?? '—' }}</td>
      <td class="small">
        @if($c->type==='file' && $c->file_url)
          <a href="{{ $c->file_url }}" target="_blank" download>تحميل الملف</a>
        @elseif(in_array($c->type,['video','link']) && $c->source_url)
          <a href="{{ $c->source_url }}" target="_blank">فتح الرابط</a>
        @else
          —
        @endif
      </td>
      <td>{!! $c->is_active ? '<span class="badge bg-success">مفعل</span>' : '<span class="badge bg-secondary">موقوف</span>' !!}</td>
      <td class="text-center">
        <a href="{{ route('admin.contents.edit',$c) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
        <form action="{{ route('admin.contents.destroy',$c) }}" method="POST" class="d-inline">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف المحتوى؟')">حذف</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="8" class="text-center text-muted">لا توجد بيانات.</td></tr>
    @endforelse
  </tbody>
</table>
</div>

{{ $contents->links('vendor.pagination.bootstrap-custom') }}
@endsection
