
@extends('admin.layouts.app')
@section('title','الجامعات')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0"><i class="bi bi-building"></i> الجامعات</h1>
  <a href="{{ route('medical.universities.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> جامعة جديدة</a>
</div>
<div class="card">
  <div class="table-responsive">
    <table class="table table-bordered align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>الكود</th>
          <th>الاسم</th>
          <th>الدولة</th>
          <th>فعال</th>
          <th>إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $x)
        <tr>
          <td>{{ $x->code }}</td>
          <td>{{ $x->name }}</td>

          <td>{{ $x->country }}</td>
          <td>{!! $x->is_active ? '<span class="badge bg-success">نعم</span>' : '<span class="badge bg-danger">لا</span>' !!}</td>
          <td>
            <a href="{{ route('medical.universities.edit',$x) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> تعديل</a>
            <form action="{{ route('medical.universities.destroy',$x) }}" method="post" class="d-inline">@csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')"><i class="bi bi-trash"></i> حذف</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" class="text-center">لا توجد جامعات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">{{ $items->links('vendor.pagination.bootstrap-custom') }}</div>
</div>
@endsection
