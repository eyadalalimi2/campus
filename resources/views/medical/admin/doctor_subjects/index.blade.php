
@extends('admin.layouts.app')
@section('title','ربط دكتور↔مادة')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0"><i class="bi bi-link"></i> ربط دكتور↔مادة</h1>
  <a href="{{ route('medical.doctor-subjects.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> ربط جديد</a>
</div>
<div class="card">
  <div class="table-responsive">
    <table class="table table-bordered align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>الدكتور</th>
          <th>المادة</th>
          <th>الأولوية</th>
          <th>مميز</th>
          <th>إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $x)
        <tr>
          <td>{{ $x->doctor->name }}</td>
          <td>{{ $x->subject->code }} — {{ $x->subject->name_ar }}</td>
          <td>{{ $x->priority }}</td>
          <td>{!! $x->featured ? '<span class="text-success"><i class="bi bi-star-fill"></i></span>' : '<span class="text-muted"><i class="bi bi-star"></i></span>' !!}</td>
          <td>
            <a href="{{ route('medical.doctor-subjects.edit',$x) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> تعديل</a>
            <form action="{{ route('medical.doctor-subjects.destroy',$x) }}" method="post" class="d-inline">@csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')"><i class="bi bi-trash"></i> حذف</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">لا توجد روابط</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">{{ $items->links() }}</div>
</div>
@endsection
