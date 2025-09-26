@extends('admin.layouts.app')
@section('title','الفيديوهات')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">الفيديوهات</h1>
  <a href="{{ route('admin.med_videos.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> جديد</a>
</div>

@includeWhen(session('success'),'admin.partials.flash_success')
@includeWhen($errors->any(),'admin.partials.flash_errors',['errors'=>$errors])

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
