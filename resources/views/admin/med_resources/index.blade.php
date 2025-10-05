@extends('admin.layouts.app')
@section('title','الملفات')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">الملفات</h1>
  <a href="{{ route('admin.med_resources.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> جديد</a>
</div>

@includeWhen(session('success'),'admin.partials.flash_success')
@includeWhen($errors->any(),'admin.partials.flash_errors',['errors'=>$errors])
<form method="GET" class="card card-body mb-3">
  <div class="row g-2">
    <div class="col-md-3">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="بحث بالعنوان/الوصف">
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
    <div class="col-md-2">
      <select name="category_id" class="form-select">
        <option value="">التصنيف — الكل</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-1">
      <select name="status" class="form-select">
        <option value="">الحالة</option>
        <option value="published" @selected(request('status')==='published')>مفعل</option>
        <option value="draft" @selected(request('status')==='draft')>موقوف</option>
      </select>
    </div>
    <div class="col-md-2">
      <div class="d-flex gap-2">
        <select name="sort" class="form-select">
          <option value="order_index" @selected(request('sort','order_index')==='order_index')>ترتيب</option>
          <option value="title" @selected(request('sort')==='title')>العنوان</option>
          <option value="id" @selected(request('sort')==='id')>#</option>
        </select>
        <select name="dir" class="form-select">
          <option value="asc" @selected(request('dir','asc')==='asc')>↑</option>
          <option value="desc" @selected(request('dir')==='desc')>↓</option>
        </select>
      </div>
    </div>
  </div>
  <div class="mt-2 d-flex gap-2">
    <button class="btn btn-primary"><i class="bi bi-filter"></i> تصفية</button>
    <a href="{{ route('admin.med_resources.index') }}" class="btn btn-light"><i class="bi bi-x-lg"></i> تفريغ</a>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-striped align-middle mb-0">
      <thead class="table-light">
        <tr><th>#</th><th>العنوان</th><th>المادة</th><th>الموضوع</th><th>التصنيف</th><th>الحالة</th><th>الاجراءات</th></tr>
      </thead>
      <tbody>
      @forelse($resources as $r)
        <tr>
          <td>{{ $r->id }}</td>
          <td>{{ $r->title }}</td>
          <td>{{ $r->subject?->name }}</td>
          <td>{{ $r->topic?->title }}</td>
          <td>{{ $r->category?->name }}</td>
          <td>
            <span class="badge {{ $r->status==='published' ? 'bg-success' : 'bg-danger' }}">
              {{ $r->status==='published' ? 'مفعل' : 'موقوف' }}
            </span>
          </td>
          <td>
            <a href="{{ route('admin.med_resources.edit',$r) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> تعديل</a>
            <form action="{{ route('admin.med_resources.destroy',$r) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف؟')">
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

<div class="mt-3">{{ $resources->links('vendor.pagination.bootstrap-custom') }}</div>
@endsection
