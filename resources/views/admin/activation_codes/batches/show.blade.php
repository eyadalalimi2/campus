@extends('admin.layouts.app')
@section('title','عرض الدفعة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">الدفعة: {{ $batch->name }} (#{{ $batch->id }})</h4>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.activation_code_batches.edit', $batch) }}" class="btn btn-primary btn-sm">تعديل</a>
    <a href="{{ route('admin.activation_code_batches.export', $batch) }}" class="btn btn-outline-success btn-sm">تصدير</a>
    <a href="{{ route('admin.activation_code_batches.index') }}" class="btn btn-outline-secondary btn-sm">رجوع</a>
  </div>
</div>

<div class="card mb-3">
  <div class="card-body">
  <div><strong>الخطة:</strong> {{ $batch->plan->name ?? '—' }}</div>
    <div><strong>النطاق:</strong>
      {{ $batch->university->name ?? '—' }}
      @if($batch->branch) / {{ $batch->branch->name }} @endif
      @if($batch->college) / {{ $batch->college->name }} @endif
      @if($batch->major)   / {{ $batch->major->name }} @endif
    </div>
    <div><strong>الكمية:</strong> {{ $batch->quantity }}</div>
    <div><strong>الحالة:</strong>
      <span class="badge
        @switch($batch->status)
          @case('active')   bg-success @break
          @case('disabled') bg-secondary @break
          @case('archived') bg-dark @break
          @default          bg-info text-dark
        @endswitch
      ">{{ $batch->status_label }}</span>
    </div>
    <div class="mt-2"><strong>ملاحظات:</strong> {{ $batch->notes ?: '—' }}</div>
  </div>
</div>

<div class="card">
  <div class="card-header">الأكواد ({{ $batch->activation_codes_count }})</div>
  <div class="table-responsive">
    <table class="table table-hover bg-white align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>الكود</th>
          <th>الحالة</th>
          <th>سياسة البدء</th>
          <th>تاريخ البدء</th>
          <th>صالح من</th>
          <th>صالح حتى</th>
        </tr>
      </thead>
      <tbody>
        @forelse($codes as $c)
          <tr>
            <td class="text-muted">{{ $c->id }}</td>
            <td class="fw-semibold">{{ $c->code }}</td>
            <td>
              @php
                $st = [
                  'active'   => 'نشط',
                  'redeemed' => 'مُسترد',
                  'expired'  => 'منتهي',
                  'disabled' => 'موقوف',
                ][$c->status] ?? $c->status;
              @endphp
              <span class="badge bg-secondary">{{ $st }}</span>
            </td>
            <td>{{ $c->start_policy === 'fixed_start' ? 'موعد ثابت' : 'عند التفعيل' }}</td>
            <td>{{ optional($c->starts_on)->format('Y-m-d') ?: '—' }}</td>
            <td>{{ optional($c->valid_from)->format('Y-m-d H:i') ?: '—' }}</td>
            <td>{{ optional($c->valid_until)->format('Y-m-d H:i') ?: '—' }}</td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted">لا توجد أكواد.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-body">
    {{ $codes->links('vendor.pagination.bootstrap-custom') }}
  </div>
</div>
@endsection
