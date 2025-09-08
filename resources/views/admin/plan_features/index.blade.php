@extends('admin.layouts.app')
@section('title','مزايا الخطة')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">مزايا الخطة: <span class="text-primary">{{ $plan->name }}</span></h4>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.plan_features.create', ['plan'=>$plan->id]) }}" class="btn btn-primary">
      <i class="bi bi-plus"></i> إضافة ميزة
    </a>
    <a href="{{ route('admin.plans.index') }}" class="btn btn-outline-secondary">رجوع للخطط</a>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-hover bg-white align-middle">
    <thead class="table-light">
      <tr>
        <th>المفتاح</th>
        <th>القيمة</th>
        <th class="text-center">إجراءات</th>
      </tr>
    </thead>
    <tbody>
      @forelse($features as $f)
      <tr>
        <td class="fw-semibold">{{ $f->feature_key }}</td>
        <td class="text-muted">{{ $f->feature_value }}</td>
        <td class="text-center">
          <a href="{{ route('admin.plan_features.edit', ['plan'=>$plan->id, 'feature'=>$f->id]) }}"
             class="btn btn-sm btn-outline-primary">تعديل</a>
          <form action="{{ route('admin.plan_features.destroy', ['plan'=>$plan->id, 'feature'=>$f->id]) }}"
                method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف الميزة؟')">حذف</button>
          </form>
        </td>
      </tr>
      @empty
      <tr><td colspan="3" class="text-center text-muted">لا توجد مزايا.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $features->links('vendor.pagination.bootstrap-custom') }}

@endsection
