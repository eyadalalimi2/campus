
@extends('admin.layouts.app')
@section('title','ربط دكتور↔مادة↔جهاز')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0"><i class="bi bi-link"></i> ربط دكتور↔مادة↔جهاز</h1>
  <a href="{{ route('medical.doctor-subject-systems.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> ربط جديد</a>
</div>
<div class="card">
  <div class="table-responsive">
    <table class="table table-bordered align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>الدكتور</th>
          <th>المادة</th>
          <th>الجهاز</th>
          <th>Playlist</th>
          <th>Tag</th>
          <th>إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $x)
        <tr>
          <td>{{ $x->doctorSubject->doctor->name }}</td>
          <td>{{ $x->doctorSubject->subject->code }} — {{ $x->doctorSubject->subject->name_ar }}</td>
          <td>{{ $x->system->name_ar }}</td>
          <td>{{ $x->playlist_id }}</td>
          <td>{{ $x->tag }}</td>
          <td>
            <a href="{{ route('medical.doctor-subject-systems.edit',$x) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> تعديل</a>
            <form action="{{ route('medical.doctor-subject-systems.destroy',$x) }}" method="post" class="d-inline">@csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')"><i class="bi bi-trash"></i> حذف</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center">لا توجد روابط</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">{{ $items->links('vendor.pagination.bootstrap-custom') }}</div>
</div>
@endsection
