@extends('admin.layouts.app')
@section('title','تصنيفات الملفات')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">تصنيفات الملفات</h1>
  <a href="{{ route('admin.med_resource-categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> جديد</a>
</div>

@includeWhen(session('success'),'admin.partials.flash_success')
@includeWhen($errors->any(),'admin.partials.flash_errors',['errors'=>$errors])

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
