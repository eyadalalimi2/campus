
@extends('admin.layouts.app')
@section('title','الأجهزة')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0"><i class="bi bi-cpu"></i> الأجهزة</h1>
  <a href="{{ route('medical.systems.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> جهاز جديد</a>
</div>
<div class="card">
  <div class="table-responsive">
    <table class="table table-bordered align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>الاسم</th>
          <th>ترتيب</th>
          <th>فعال</th>
          <th>إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $x)
        <tr>

          <td>{{ $x->name_ar }}</td>
          <td>{{ $x->display_order }}</td>
          <td>{!! $x->is_active ? '<span class="badge bg-success">نعم</span>' : '<span class="badge bg-danger">لا</span>' !!}</td>
          <td>
            <a href="{{ route('medical.systems.edit',$x) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> تعديل</a>
            <form action="{{ route('medical.systems.destroy',$x) }}" method="post" class="d-inline">@csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')"><i class="bi bi-trash"></i> حذف</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">لا توجد أجهزة</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">{{ $items->links() }}</div>
</div>
@endsection
