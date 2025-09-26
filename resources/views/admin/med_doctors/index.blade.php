@extends('admin.layouts.app')
@section('title','الدكاترة')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">الدكاترة</h1>
  <a href="{{ route('admin.med_doctors.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> جديد</a>
</div>

@includeWhen(session('success'),'admin.partials.flash_success')
@includeWhen($errors->any(),'admin.partials.flash_errors',['errors'=>$errors])
<form method="GET" class="card card-body mb-3">
  <div class="row g-2">
    <div class="col-md-5">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="بحث بالاسم/النبذة">
    </div>
    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">الحالة — الكل</option>
        <option value="published" @selected(request('status')==='published')>منشور</option>
        <option value="draft" @selected(request('status')==='draft')>مسودة</option>
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
    <a href="{{ route('admin.med_doctors.index') }}" class="btn btn-light"><i class="bi bi-x-lg"></i> تفريغ</a>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-striped align-middle mb-0">
      <thead class="table-light">
        <tr><th>#</th><th>الاسم</th><th>الترتيب</th><th>الحالة</th><th>تحكم</th></tr>
      </thead>
      <tbody>
      @forelse($doctors as $d)
        <tr>
          <td>{{ $d->id }}</td>
          <td>{{ $d->name }}</td>
          <td>{{ $d->order_index }}</td>
          <td><span class="badge bg-{{ $d->status==='published'?'success':'secondary' }}">{{ $d->status }}</span></td>
          <td>
            <a href="{{ route('admin.med_doctors.edit',$d) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> تعديل</a>
            <form action="{{ route('admin.med_doctors.destroy',$d) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف؟')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> حذف</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-center">لا توجد بيانات</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="mt-3">{{ $doctors->links('vendor.pagination.bootstrap-custom') }}</div>
@endsection
