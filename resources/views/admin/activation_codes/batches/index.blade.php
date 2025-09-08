@extends('admin.layouts.app')
@section('title','دفعات الأكواد')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">دفعات الأكواد</h4>
  <a href="{{ route('admin.activation_code_batches.create') }}" class="btn btn-primary">
    <i class="bi bi-plus"></i> دفعة جديدة
  </a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-4">
    <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="بحث باسم الدفعة">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-secondary w-100">بحث</button>
  </div>
</form>

<div class="table-responsive">
<table class="table table-hover bg-white align-middle">
  <thead class="table-light">
    <tr>
      <th>#</th>
      <th>الاسم</th>
      <th>الخطة</th>
      <th>الكمية</th>
      <th>مُستعمل</th>
      <th>الحالة</th>
      <th>أُنشئت</th>
      <th class="text-center">إجراءات</th>
    </tr>
  </thead>
  <tbody>
    @forelse($batches as $b)
    <tr>
      <td>{{ $b->id }}</td>
      <td>{{ $b->display_name }}</td>
      <td>{{ $b->plan->name ?? ('#'.$b->plan_id) }}</td>
      <td>{{ $b->activation_codes_count ?? $b->quantity }}</td>
      <td>{{ $b->redeemed_count ?? 0 }}</td>
      <td>
        @php $st = $b->status; @endphp
        <span class="badge {{ $st==='active'?'bg-success':($st==='disabled'?'bg-secondary':($st==='archived'?'bg-dark':'bg-info text-dark')) }}">
          {{ $st }}
        </span>
      </td>
      <td>{{ optional($b->created_at)->format('Y-m-d H:i') }}</td>
      <td class="text-center">
        <a href="{{ route('admin.activation_code_batches.show', $b) }}" class="btn btn-sm btn-outline-secondary">عرض</a>
        <a href="{{ route('admin.activation_code_batches.edit', $b) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
        <a href="{{ route('admin.activation_code_batches.export', $b) }}" class="btn btn-sm btn-success">
          <i class="bi bi-download"></i> تصدير
        </a>
        <form action="{{ route('admin.activation_code_batches.disable', $b) }}" method="POST" class="d-inline">
          @csrf
          <button class="btn btn-sm btn-warning">إيقاف</button>
        </form>
        <form action="{{ route('admin.activation_code_batches.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('حذف الدفعة؟')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">حذف</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="8" class="text-center text-muted">لا توجد دفعات.</td></tr>
    @endforelse
  </tbody>
</table>
</div>

{{ $batches->links('vendor.pagination.bootstrap-custom') }}
@endsection
