@extends('admin.layouts.app')
@section('title','الأجهزة')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">الأجهزة</h1>
  <a href="{{ route('admin.med_devices.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> جديد</a>
</div>

@includeWhen(session('success'),'admin.partials.flash_success')
@includeWhen($errors->any(),'admin.partials.flash_errors',['errors'=>$errors])
<form method="GET" class="card card-body mb-3">
  <div class="row g-2">
    <div class="col-md-4">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="بحث بالاسم ">
    </div>
    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">الحالة — الكل</option>
        <option value="published" @selected(request('status')==='published')>مفعل</option>
        <option value="draft" @selected(request('status')==='draft')>موقوف</option>
      </select>
    </div>
    <div class="col-md-3">
      <select name="sort" class="form-select">
        <option value="order_index" @selected(request('sort','order_index')==='order_index')>ترتيب</option>
        <option value="name" @selected(request('sort')==='name')>الاسم</option>
        <option value="id" @selected(request('sort')==='id')>#</option>
      </select>
    </div>
    <div class="col-md-2">
      <select name="dir" class="form-select">
        <option value="asc" @selected(request('dir','asc')==='asc')>تصاعدي</option>
        <option value="desc" @selected(request('dir')==='desc')>تنازلي</option>
      </select>
    </div>
  </div>
  <div class="mt-2 d-flex gap-2">
    <button class="btn btn-primary"><i class="bi bi-filter"></i> تصفية</button>
    <a href="{{ route('admin.med_devices.index') }}" class="btn btn-light"><i class="bi bi-x-lg"></i> تفريغ</a>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-striped align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>الصورة</th>
          <th>الاسم</th>
          <th>المواد المرتبطة</th>
          <th>الترتيب</th>
          <th>الحالة</th>
          <th>تحكم</th>
        </tr>
      </thead>
      <tbody>
      @forelse($devices as $d)
        <tr>
          <td>{{ $d->id }}</td>
          <td>
            @if($d->image_path)
              <img src="{{ asset('storage/'.$d->image_path) }}" alt="صورة" class="img-thumbnail" style="height:40px;width:40px;object-fit:cover">
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>{{ $d->name }}</td>
          <td>
            @if(method_exists($d,'subjects') && $d->subjects && count($d->subjects))
              @foreach($d->subjects as $s)
                <span class="badge bg-primary text-white mb-1">{{ $s->name }}</span>
              @endforeach
            @else
              <span class="text-muted">—</span>
            @endif
          </td>
          <td>{{ $d->order_index }}</td>
          <td>
            <span class="badge {{ $d->status==='published' ? 'bg-success' : 'bg-danger' }}">
              {{ $d->status==='published' ? 'مفعل' : 'موقوف' }}
            </span>
          </td>
          <td>
            <a href="{{ route('admin.med_devices.edit',$d) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> تعديل</a>
            <form action="{{ route('admin.med_devices.destroy',$d) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف؟')">
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

<div class="mt-3">{{ $devices->links('vendor.pagination.bootstrap-custom') }}</div>
@endsection
