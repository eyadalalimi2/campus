@extends('admin.layouts.app')
@section('title','الاشتراكات')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">الاشتراكات</h4>
  <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary">
    <i class="bi bi-plus"></i> اشتراك جديد
  </a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-4">
    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="بحث باسم/إيميل/رقم أكاديمي">
  </div>
  <div class="col-md-3">
    <select name="status" class="form-select" onchange="this.form.submit()">
      <option value="">— الحالة —</option>
      <option value="active" @selected(request('status')==='active')>نشط</option>
      <option value="expired" @selected(request('status')==='expired')>منتهي</option>
      <option value="canceled" @selected(request('status')==='canceled')>ملغي</option>
    </select>
  </div>
  <div class="col-md-3">
    <input type="text" name="plan" value="{{ request('plan') }}" class="form-control" placeholder="الخطة (standard/premium)">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-secondary w-100">بحث</button>
  </div>
</form>

<div class="table-responsive">
<table class="table table-hover bg-white align-middle">
  <thead class="table-light">
    <tr>
      <th>الطالب</th><th>الخطة</th><th>الحالة</th><th>بداية</th><th>نهاية</th><th>تجديد</th><th>السعر</th><th class="text-center">إجراءات</th>
    </tr>
  </thead>
  <tbody>
    @forelse($subs as $s)
    <tr>
      <td class="fw-semibold">{{ $s->user?->name ?? '—' }}</td>
      <td class="small">{{ $s->plan }}</td>
      <td>
        @if($s->status==='active') <span class="badge bg-success">نشط</span>
        @elseif($s->status==='expired') <span class="badge bg-secondary">منتهي</span>
        @else <span class="badge bg-dark">ملغي</span>
        @endif
      </td>
      <td class="small text-muted">{{ $s->started_at?->format('Y-m-d') ?? '—' }}</td>
      <td class="small text-muted">{{ $s->ends_at?->format('Y-m-d') ?? '—' }}</td>
      <td class="small">{{ $s->auto_renew ? 'نعم' : 'لا' }}</td>
      <td class="small">{{ $s->price_cents ? number_format($s->price_cents).' '.$s->currency : '—' }}</td>
      <td class="text-center">
        <a href="{{ route('admin.subscriptions.edit',$s) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
        <form action="{{ route('admin.subscriptions.destroy',$s) }}" method="POST" class="d-inline">@csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف الاشتراك؟')">حذف</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="8" class="text-center text-muted">لا توجد بيانات.</td></tr>
    @endforelse
  </tbody>
</table>
</div>

{{ $subs->links('vendor.pagination.bootstrap-custom') }}
@endsection
