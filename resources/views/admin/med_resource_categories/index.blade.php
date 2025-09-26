@extends('admin.layouts.app')
@section('title','تصنيفات الملفات')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">تصنيفات الملفات</h1>
  <a href="{{ route('admin.med_resource-categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> جديد</a>
</div>

@includeWhen(session('success'),'admin.partials.flash_success')
@includeWhen($errors->any(),'admin.partials.flash_errors',['errors'=>$errors])
<form method="GET" class="card card-body mb-3">
  <div class="row g-2">
    <div class="col-md-5">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="بحث بالاسم/الكود">
    </div>
    <div class="col-md-3">
      <select name="active" class="form-select">
        <option value="">الحالة — الكل</option>
        <option value="1" @selected(request('active')==='1')>فعال</option>
        <option value="0" @selected(request('active')==='0')>غير فعال</option>
      </select>
    </div>
    <div class="col-md-2">
      <select name="sort" class="form-select">
        <option value="order_index" @selected(request('sort','order_index')==='order_index')>ترتيب</option>
        <option value="name" @selected(request('sort')==='name')>الاسم</option>
        <option value="id" @selected(request('sort')==='id')>#</option>
      </select>
    </div>
    <div class="col-md-2">
      <select name="dir" class="form-select">
        <option value="asc" @selected(request('dir','asc')==='asc')>↑</option>
        <option value="desc" @selected(request('dir')==='desc')>↓</option>
      </select>
    </div>
  </div>
  <div class="mt-2 d-flex gap-2">
    <button class="btn btn-primary"><i class="bi bi-filter"></i> تصفية</button>
    <a href="{{ route('admin.med_resource-categories.index') }}" class="btn btn-light"><i class="bi bi-x-lg"></i> تفريغ</a>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-striped align-middle mb-0">
      <thead class="table-light">
        <tr><th>#</th><th>الاسم</th><th>الكود</th><th>الترتيب</th><th>الحالة</th><th>تحكم</th></tr>
      </thead>
      <tbody>
      @forelse($categories as $c)
        <tr>
          <td>{{ $c->id }}</td>
          <td>{{ $c->name }}</td>
          <td><code>{{ $c->code }}</code></td>
          <td>{{ $c->order_index }}</td>
          <td><span class="badge bg-{{ $c->active?'success':'secondary' }}">{{ $c->active?'فعال':'غير فعال' }}</span></td>
          <td>
            <a href="{{ route('admin.med_resource-categories.edit',$c) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> تعديل</a>
            <form action="{{ route('admin.med_resource-categories.destroy',$c) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف؟')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> حذف</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-center">لا توجد بيانات</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">{{ $categories->links('vendor.pagination.bootstrap-custom') }}</div>
@endsection
