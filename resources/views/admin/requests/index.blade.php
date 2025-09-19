@extends('admin.layouts.app')
@section('title','الطلبات الطلابية')

@section('content')
<h1 class="h4 mb-3">الطلبات الطلابية</h1>

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-3">
    <label class="form-label">الحالة</label>
    <select name="status" class="form-select" onchange="this.form.submit()">
      <option value="">الكل</option>
      <option value="open" @selected(request('status')==='open')>مفتوح</option>
      <option value="in_progress" @selected(request('status')==='in_progress')>قيد المعالجة</option>
      <option value="closed" @selected(request('status')==='closed')>مغلق</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">نوع الطلب</label>
    <input type="text" name="type" class="form-control" value="{{ request('type') }}" placeholder="support/academic/...">
  </div>
  <div class="col-md-4">
    <label class="form-label">بحث</label>
    <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="الموضوع/المحتوى/المستخدم">
  </div>
  <div class="col-md-2 d-flex align-items-end">
    <button class="btn btn-outline-secondary w-100">تصفية</button>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>المستخدم</th>
          <th>النوع</th>
          <th>الموضوع</th>
          <th>الحالة</th>
          <th>المُعيّن</th>
          <th>أُنشئ</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($requests as $r)
          <tr>
            <td>{{ $r->id }}</td>
            <td>
              @if($r->user) <div class="small">{{ $r->user->name }}</div>@endif
              <div class="text-muted small">{{ $r->user?->email }}</div>
            </td>
            <td><span class="badge bg-info-subtle text-dark">{{ $r->type }}</span></td>
            <td class="text-truncate" style="max-width:260px">{{ $r->subject }}</td>
            <td>
              @php
                $map = ['open'=>'success','in_progress'=>'warning','resolved'=>'info','rejected'=>'danger','closed'=>'secondary'];
                $statusMap = [
                  'open' => 'مفتوح',
                  'in_progress' => 'قيد المعالجة',
                  'resolved' => 'تم الحل',
                  'rejected' => 'مرفوض',
                  'closed' => 'مغلق',
                ];
              @endphp
              <span class="badge bg-{{ $map[$r->status] ?? 'secondary' }}">{{ $statusMap[$r->status] ?? $r->status }}</span>
            </td>
            <td>{{ $r->assignee?->name ?? '—' }}</td>
            <td class="small text-muted">{{ $r->created_at?->format('Y-m-d H:i') }}</td>
            <td class="text-nowrap">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.requests.show',$r) }}">عرض</a>
              <form method="POST" action="{{ route('admin.requests.destroy', $r) }}" style="display:inline" onsubmit="return confirm('هل أنت متأكد من حذف الطلب؟');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">حذف</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted">لا توجد طلبات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">
    {{ $requests->withQueryString()->links('vendor.pagination.bootstrap-custom') }}
  </div>
</div>
@endsection
