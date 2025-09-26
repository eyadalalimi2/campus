@extends('admin.layouts.app')
@section('title','الملفات')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">الملفات</h1>
  <a href="{{ route('admin.med_resources.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> جديد</a>
</div>

@includeWhen(session('success'),'admin.partials.flash_success')
@includeWhen($errors->any(),'admin.partials.flash_errors',['errors'=>$errors])

<div class="card">
  <div class="table-responsive">
    <table class="table table-striped align-middle mb-0">
      <thead class="table-light">
        <tr><th>#</th><th>العنوان</th><th>المادة</th><th>الموضوع</th><th>التصنيف</th><th>الحالة</th><th>تحكم</th></tr>
      </thead>
      <tbody>
      @forelse($resources as $r)
        <tr>
          <td>{{ $r->id }}</td>
          <td>{{ $r->title }}</td>
          <td>{{ $r->subject?->name }}</td>
          <td>{{ $r->topic?->title }}</td>
          <td>{{ $r->category?->name }}</td>
          <td><span class="badge bg-{{ $r->status==='published'?'success':'secondary' }}">{{ $r->status }}</span></td>
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
