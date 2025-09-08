@extends('admin.layouts.app')
@section('title','الخطط')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">الخطط</h4>
  <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
    <i class="bi bi-plus"></i> إضافة خطة
  </a>
</div>

<div class="table-responsive">
  <table class="table table-hover bg-white align-middle">
    <thead class="table-light">
      <tr>
        <th>الاسم</th>
        <th>المدة (يوم)</th>
        <th>الحالة</th>
        <th>عدد المزايا</th>
        <th class="text-center">إجراءات</th>
      </tr>
    </thead>
    <tbody>
      @forelse($plans as $p)
      <tr>
        <td class="fw-semibold">{{ $p->name }}</td>
        <td>{{ $p->duration_days }}</td>
        <td>{!! $p->is_active ? '<span class="badge bg-success">مفعل</span>' : '<span class="badge bg-secondary">موقوف</span>' !!}</td>
        <td><span class="badge bg-info text-dark">{{ $p->features_count }}</span></td>
        <td class="text-center">
          <a href="{{ route('admin.plan_features.index', ['plan' => $p->id]) }}"
             class="btn btn-sm btn-outline-secondary">المزايا</a>
          <a href="{{ route('admin.plans.edit', $p) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
          <form action="{{ route('admin.plans.destroy', $p) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف الخطة؟')">حذف</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="5" class="text-center text-muted">لا توجد بيانات.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $plans->links('vendor.pagination.bootstrap-custom') }}

@endsection
