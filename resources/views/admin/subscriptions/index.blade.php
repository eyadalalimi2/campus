@extends('admin.layouts.app')
@section('title','الاشتراكات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">الاشتراكات</h4>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.plans.index') }}" class="btn btn-info">
      <i class="bi bi-list"></i> الخطط والمميزات
    </a>
    <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary">
      <i class="bi bi-key"></i> تفعيل كود لطالب
    </a>
  </div>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-3">
    <input type="text" name="q" class="form-control" value="{{ request('q') }}"
           placeholder="بحث: اسم/إيميل/رقم أكاديمي/كود">
  </div>
  <div class="col-md-2">
    <select name="status" class="form-select" onchange="this.form.submit()">
      <option value="">— الحالة —</option>
      @php $st = request('status'); @endphp
      <option value="active"    @selected($st==='active')>نشط</option>
      <option value="expired"   @selected($st==='expired')>منتهي</option>
      <option value="cancelled" @selected($st==='cancelled')>ملغى</option>
      <option value="pending"   @selected($st==='pending')>قيد التفعيل</option>
    </select>
  </div>
  <div class="col-md-3">
    <select name="plan_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الخطط —</option>
      @foreach($plans as $p)
        <option value="{{ $p->id }}" @selected(request('plan_id')==$p->id)>{{ $p->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <input type="date" name="from" class="form-control" value="{{ request('from') }}" placeholder="من تاريخ">
  </div>
  <div class="col-md-2">
    <input type="date" name="to" class="form-control" value="{{ request('to') }}" placeholder="إلى تاريخ">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-secondary w-100">بحث</button>
  </div>
</form>

<div class="table-responsive">
<table class="table table-hover bg-white align-middle">
  <thead class="table-light">
    <tr>
      <th>الطالب</th>
      <th>الخطة</th>
      <th>الكود</th>
      <th>البداية</th>
      <th>النهاية</th>
      <th>الحالة</th>
      <th class="text-center">إجراءات</th>
    </tr>
  </thead>
  <tbody>
    @forelse($subs as $s)
      <tr>
        <td class="fw-semibold">
          {{ $s->user?->name ?? '—' }}
          <div class="small text-muted">{{ $s->user?->email }}</div>
        </td>
        <td>{{ $s->plan?->name ?? '—' }}</td>
        <td class="small">{{ $s->activationCode?->code ?? '—' }}</td>
        <td>{{ optional($s->started_at)->format('Y-m-d') ?: '—' }}</td>
        <td>{{ optional($s->ends_at)->format('Y-m-d') ?: '—' }}</td>
        <td>
          @php $cls = [
            'active'    => 'success',
            'expired'   => 'secondary',
            'cancelled' => 'dark',
            'pending'   => 'info text-dark',
          ][$s->status] ?? 'light text-dark'; @endphp
          <span class="badge bg-{{ $cls }}">{{ $s->status_label }}</span>
        </td>
        <td class="text-center">
          <a href="{{ route('admin.subscriptions.edit', $s) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
          <form action="{{ route('admin.subscriptions.destroy', $s) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف الاشتراك؟')">حذف</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="7" class="text-center text-muted">لا توجد بيانات.</td></tr>
    @endforelse
  </tbody>
</table>
</div>

{{ $subs->links('vendor.pagination.bootstrap-custom') }}
@endsection
