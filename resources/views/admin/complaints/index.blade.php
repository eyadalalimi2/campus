@extends('admin.layouts.app')
@section('title','الشكاوى')

@section('content')
<h1 class="h4 mb-3">الشكاوى</h1>

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-3">
    <label class="form-label">الحالة</label>
    <select name="status" class="form-select" onchange="this.form.submit()">
      <option value="">الكل</option>
      @foreach(['open'=>'مفتوحة','triaged'=>'مفرزة','in_progress'=>'قيد المعالجة','resolved'=>'محلولة','rejected'=>'مرفوضة','closed'=>'مغلقة'] as $k=>$v)
        <option value="{{ $k }}" @selected(request('status')===$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">الخطورة</label>
    <select name="severity" class="form-select" onchange="this.form.submit()">
      <option value="">الكل</option>
      @foreach(['low'=>'منخفضة','medium'=>'متوسطة','high'=>'مرتفعة','critical'=>'حرجة'] as $k=>$v)
        <option value="{{ $k }}" @selected(request('severity')===$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">النوع</label>
    <select name="type" class="form-select" onchange="this.form.submit()">
      <option value="">الكل</option>
      @foreach(['content'=>'محتوى','asset'=>'عنصر','user'=>'مستخدم','bug'=>'عُطل','abuse'=>'إساءة','other'=>'أخرى'] as $k=>$v)
        <option value="{{ $k }}" @selected(request('type')===$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">بحث</label>
    <div class="input-group">
      <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="الموضوع/النص/المستخدم">
      <button class="btn btn-outline-secondary" type="submit">تصفية</button>
      <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-light border">تفريغ</a>
    </div>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>المستخدم</th>
          <th>الموضوع</th>
          <th>النوع</th>
          <th>الخطورة</th>
          <th>الحالة</th>
          <th>المُعيّن</th>
          <th>أُنشئ</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($complaints as $c)
          @php
            $sevMapBg = ['low'=>'success','medium'=>'warning','high'=>'danger','critical'=>'dark'];
            $statusMap = ['open'=>'primary','triaged'=>'info','in_progress'=>'warning','resolved'=>'success','rejected'=>'secondary','closed'=>'dark'];
            $typeLabels = ['content'=>'محتوى','asset'=>'عنصر','user'=>'مستخدم','bug'=>'عُطل','abuse'=>'إساءة','other'=>'أخرى'];
            $sevLabels  = ['low'=>'منخفضة','medium'=>'متوسطة','high'=>'مرتفعة','critical'=>'حرجة'];
            $statusLabels = ['open'=>'مفتوحة','triaged'=>'مفرزة','in_progress'=>'قيد المعالجة','resolved'=>'محلولة','rejected'=>'مرفوضة','closed'=>'مغلقة'];
          @endphp
          <tr>
            <td class="text-muted">#{{ $c->id }}</td>
            <td>
              <div class="small fw-semibold">{{ optional($c->user)->name ?? '—' }}</div>
              <div class="text-muted small">{{ optional($c->user)->email ?? '—' }}</div>
            </td>
            <td class="text-truncate" style="max-width:260px" title="{{ $c->subject }}">{{ $c->subject ?? '—' }}</td>
            <td><span class="badge bg-info-subtle text-dark">{{ $typeLabels[$c->type] ?? ($c->type ?? '—') }}</span></td>
            <td>
              @php $sev = $c->severity ?? null; @endphp
              <span class="badge bg-{{ $sevMapBg[$sev] ?? 'secondary' }}">{{ $sevLabels[$sev] ?? '—' }}</span>
            </td>
            <td>
              @php $st = $c->status ?? null; @endphp
              <span class="badge bg-{{ $statusMap[$st] ?? 'secondary' }}">{{ $statusLabels[$st] ?? '—' }}</span>
            </td>
            <td>{{ optional($c->assignee)->name ?? '—' }}</td>
            <td class="small text-muted">
              @if($c->created_at instanceof \Illuminate\Support\Carbon)
                {{ $c->created_at->format('Y-m-d H:i') }}
              @else
                {{ \Carbon\Carbon::parse($c->created_at)->format('Y-m-d H:i') }}
              @endif
            </td>
            <td class="text-nowrap">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.complaints.show',$c) }}">عرض</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="9" class="text-center text-muted py-4">لا توجد شكاوى</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">
    {{ $complaints->withQueryString()->links('vendor.pagination.bootstrap-custom') }}
  </div>
</div>
@endsection
