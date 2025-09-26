@extends('admin.layouts.app')
@section('title','الفيديوهات')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">الفيديوهات</h1>
  <a href="{{ route('admin.med_videos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> جديد</a>
</div>

@includeWhen(session('success'),'admin.partials.flash_success')
@includeWhen($errors->any(),'admin.partials.flash_errors',['errors'=>$errors])
<form method="GET" class="card card-body mb-3">
  <div class="row g-2">
    <div class="col-md-3">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="بحث بالعنوان/رابط يوتيوب">
    </div>
    <div class="col-md-2">
      <select name="doctor_id" class="form-select">
        <option value="">الدكتور — الكل</option>
        @foreach($doctors as $d)
          <option value="{{ $d->id }}" @selected(request('doctor_id')==$d->id)>{{ $d->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <select name="subject_id" class="form-select">
        <option value="">المادة — الكل</option>
        @foreach($subjects as $s)
          <option value="{{ $s->id }}" @selected(request('subject_id')==$s->id)>{{ $s->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <select name="topic_id" class="form-select">
        <option value="">الموضوع — الكل</option>
        @foreach($topics as $t)
          <option value="{{ $t->id }}" @selected(request('topic_id')==$t->id)>{{ $t->title }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <div class="d-flex gap-2">
        <input type="date" name="from" value="{{ request('from') }}" class="form-control" title="من تاريخ">
        <input type="date" name="to" value="{{ request('to') }}" class="form-control" title="إلى تاريخ">
      </div>
    </div>
  </div>
  <div class="row g-2 mt-1">
    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">الحالة — الكل</option>
        <option value="published" @selected(request('status')==='published')>منشور</option>
        <option value="draft" @selected(request('status')==='draft')>مسودة</option>
      </select>
    </div>
    <div class="col-md-3">
      <select name="sort" class="form-select">
        <option value="order_index" @selected(request('sort','order_index')==='order_index')>ترتيب</option>
        <option value="published_at" @selected(request('sort')==='published_at')>تاريخ النشر</option>
        <option value="title" @selected(request('sort')==='title')>العنوان</option>
        <option value="id" @selected(request('sort')==='id')>#</option>
      </select>
    </div>
    <div class="col-md-2">
      <select name="dir" class="form-select">
        <option value="asc" @selected(request('dir','asc')==='asc')>↑</option>
        <option value="desc" @selected(request('dir')==='desc')>↓</option>
      </select>
    </div>
    <div class="col-md-4 d-flex gap-2">
      <button class="btn btn-primary flex-fill"><i class="bi bi-filter"></i> تصفية</button>
      <a href="{{ route('admin.med_videos.index') }}" class="btn btn-light"><i class="bi bi-x-lg"></i> تفريغ</a>
    </div>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-striped align-middle mb-0">
      <thead class="table-light">
        <tr><th>#</th><th>العنوان</th><th>الدكتور</th><th>المادة</th><th>الموضوع</th><th>الحالة</th><th>تحكم</th></tr>
      </thead>
      <tbody>
      @forelse($videos as $v)
        <tr>
          <td>{{ $v->id }}</td>
          <td>{{ $v->title }}</td>
          <td>{{ $v->doctor?->name }}</td>
          <td>{{ $v->subject?->name }}</td>
          <td>{{ $v->topic?->title }}</td>
          <td><span class="badge bg-{{ $v->status==='published'?'success':'secondary' }}">{{ $v->status }}</span></td>
          <td>
            <a href="{{ route('admin.med_videos.edit',$v) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> تعديل</a>
            <form action="{{ route('admin.med_videos.destroy',$v) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف؟')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> حذف</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="7" class="text-center">لا توجد بيانات</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">{{ $videos->links('vendor.pagination.bootstrap-custom') }}</div>
@endsection
