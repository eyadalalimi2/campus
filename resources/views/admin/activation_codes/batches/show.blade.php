@extends('admin.layouts.app')
@section('title','دفعة #'.$batch->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">دفعة الأكواد: {{ $batch->display_name }}</h4>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.activation_code_batches.edit', $batch) }}" class="btn btn-outline-primary btn-sm">تعديل</a>
    <a href="{{ route('admin.activation_code_batches.export', $batch) }}" class="btn btn-success btn-sm">
      <i class="bi bi-download"></i> تصدير CSV
    </a>
    <a href="{{ route('admin.activation_code_batches.index') }}" class="btn btn-outline-secondary btn-sm">رجوع</a>
  </div>
</div>

<div class="card mb-3">
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-3"><strong>الخطة:</strong> {{ $batch->plan->name ?? ('#'.$batch->plan_id) }}</div>
      <div class="col-md-3"><strong>الكمية:</strong> {{ $batch->activationCodes()->count() }}</div>
      <div class="col-md-3"><strong>مستعمل:</strong> {{ $batch->activationCodes()->where('redemptions_count','>',0)->count() }}</div>
      <div class="col-md-3"><strong>الحالة:</strong> <span class="badge bg-info text-dark">{{ $batch->status }}</span></div>
      <div class="col-md-12"><strong>ملاحظات:</strong> {{ $batch->notes ?: '—' }}</div>
      <div class="col-md-12"><strong>النطاق:</strong>
        {{ $batch->university->name ?? '—' }}
        @if($batch->college) / {{ $batch->college->name }} @endif
        @if($batch->major) / {{ $batch->major->name }} @endif
      </div>
      <div class="col-md-12"><strong>الصلاحية:</strong>
        مدة {{ $batch->duration_days }} يوم —
        سياسة {{ $batch->start_policy }}
        @if($batch->start_policy==='fixed_start' && $batch->starts_on) من {{ $batch->starts_on->format('Y-m-d') }} @endif
        @if($batch->valid_from) | صالح من {{ $batch->valid_from->format('Y-m-d H:i') }} @endif
        @if($batch->valid_until) | حتى {{ $batch->valid_until->format('Y-m-d H:i') }} @endif
      </div>
    </div>
  </div>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-3">
    <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="بحث بالكود">
  </div>
  <div class="col-md-3">
    <select name="status" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الحالات —</option>
      @foreach(['active','redeemed','expired','disabled'] as $st)
        <option value="{{ $st }}" @selected(request('status')===$st)>{{ $st }}</option>
      @endforeach
    </select>
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
      <th>الكود</th>
      <th>الحالة</th>
      <th>مستعمل/مرات</th>
      <th>تاريخ الإنشاء</th>
    </tr>
  </thead>
  <tbody>
    @forelse($codes as $c)
    <tr>
      <td>{{ $c->id }}</td>
      <td class="fw-semibold">{{ $c->code }}</td>
      <td>{{ $c->status }}</td>
      <td>
        {{ $c->redemptions_count }}/{{ $c->max_redemptions }}
        @if($c->redeemed_by_user_id)
          <div class="small text-muted">User# {{ $c->redeemed_by_user_id }} @ {{ optional($c->redeemed_at)->format('Y-m-d H:i') }}</div>
        @endif
      </td>
      <td>{{ optional($c->created_at)->format('Y-m-d H:i') }}</td>
    </tr>
    @empty
    <tr><td colspan="5" class="text-center text-muted">لا توجد أكواد.</td></tr>
    @endforelse
  </tbody>
</table>
</div>

{{ $codes->links('vendor.pagination.bootstrap-custom') }}
@endsection
