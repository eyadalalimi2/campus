@extends('admin.layouts.app')
@section('title','آراء المستخدمين')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">آراء المستخدمين (تقييم التطبيق)</h1>
  <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-repeat"></i> تحديث
  </a>
</div>

<div class="card mb-3">
  <div class="card-header bg-white fw-semibold">
    <i class="bi bi-funnel"></i> تصفية النتائج
  </div>
  <div class="card-body">
    <form method="GET" class="row g-3 align-items-end">
      <div class="col-md-3">
        <label class="form-label">التقييم</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-star-half"></i></span>
          <select name="rating" class="form-select" onchange="this.form.submit()">
            <option value="">الكل</option>
            @for($i=5;$i>=1;$i--)
              <option value="{{ $i }}" @selected(request('rating')==$i)>{{ $i }} نجوم</option>
            @endfor
          </select>
        </div>
      </div>

      <div class="col-md-3">
        <label class="form-label">الردود</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-chat-left-dots"></i></span>
          <select name="reply_filter" class="form-select" onchange="this.form.submit()">
            <option value="">الكل</option>
            <option value="has" @selected(request('reply_filter')==='has')>يوجد رد</option>
            <option value="none" @selected(request('reply_filter')==='none')>بدون رد</option>
          </select>
        </div>
      </div>

      <div class="col-md-3">
        <label class="form-label">الحالة</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-flag"></i></span>
          <select name="status" class="form-select" onchange="this.form.submit()">
            @php $st = request('status'); @endphp
            <option value="" @selected(!$st)>الكل</option>
            <option value="pending" @selected($st==='pending')>بانتظار</option>
            <option value="approved" @selected($st==='approved')>معتمد</option>
            <option value="rejected" @selected($st==='rejected')>مرفوض</option>
          </select>
        </div>
      </div>

      <div class="col-md-3">
        <label class="form-label">بحث</label>
        <div class="input-group">
          <span class="input-group-text"><i class="bi bi-search"></i></span>
          <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="الاسم / البريد / التعليق">
          <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-funnel"></i> تصفية</button>
          <a href="{{ route('admin.reviews.index') }}" class="btn btn-primary text-white"><i class="bi bi-x-lg"></i> تفريغ</a>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr class="table-light">
          <th>#</th>
          <th>المستخدم</th>
          <th>التقييم</th>
          <th>التعليق</th>
          <th>رد الإدارة</th>
          <th>الحالة</th>
          <th>أُنشئ</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($reviews as $r)
          <tr class="align-middle">
            <td class="text-muted">#{{ $r->id }}</td>
            <td>
              <div class="small fw-semibold">{{ optional($r->user)->name ?? '—' }}</div>
              <div class="text-muted small">{{ optional($r->user)->email ?? '—' }}</div>
            </td>
            <td>
              @for($i=1;$i<=5;$i++)
                @if($i <= (int)$r->rating)
                  <span class="text-warning">★</span>
                @else
                  <span class="text-muted">☆</span>
                @endif
              @endfor
              <div class="small text-muted">{{ (int)$r->rating }} / 5</div>
            </td>
            <td class="text-truncate" style="max-width:300px" title="{{ $r->comment }}">{{ $r->comment ?? '—' }}</td>
            <td class="text-truncate" style="max-width:300px" title="{{ $r->reply_text }}">
              @if($r->reply_text)
                <div>{{ \Illuminate\Support\Str::limit($r->reply_text, 80) }}</div>
                <div class="small text-muted">بواسطة: {{ optional($r->replyAdmin)->name ?? '—' }} @if($r->replied_at) • {{ $r->replied_at->format('Y-m-d H:i') }} @endif</div>
              @else
                <span class="badge bg-secondary">لا يوجد</span>
              @endif
            </td>
            <td>
              @php
                $statusMap = [
                  'approved' => ['label' => 'معتمد', 'class' => 'success'],
                  'pending'  => ['label' => 'بانتظار', 'class' => 'warning'],
                  'rejected' => ['label' => 'مرفوض', 'class' => 'secondary'],
                ];
                $st = strtolower($r->status ?? 'approved');
                $stCfg = $statusMap[$st] ?? $statusMap['approved'];
              @endphp
              <span class="badge bg-{{ $stCfg['class'] }}">{{ $stCfg['label'] }}</span>
            </td>
            <td class="small text-muted">
              @if($r->created_at instanceof \Illuminate\Support\Carbon)
                {{ $r->created_at->format('Y-m-d H:i') }}
              @else
                {{ \Carbon\Carbon::parse($r->created_at)->format('Y-m-d H:i') }}
              @endif
            </td>
            @php $st = strtolower($r->status ?? 'approved'); @endphp
            <td class="text-nowrap d-flex gap-1">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.reviews.show', $r) }}" title="عرض">
                <i class="bi bi-eye"></i>
              </a>
              <form action="{{ route('admin.reviews.status', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('تأكيد: اعتماد هذا التقييم؟');">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="approved">
                <button class="btn btn-sm btn-success" title="اعتماد" @if($st==='approved') disabled @endif>
                  <i class="bi bi-check2"></i>
                </button>
              </form>
              <form action="{{ route('admin.reviews.status', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('تأكيد: وضع هذا التقييم قيد الانتظار؟');">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="pending">
                <button class="btn btn-sm btn-warning" title="انتظار" @if($st==='pending') disabled @endif>
                  <i class="bi bi-hourglass"></i>
                </button>
              </form>
              <form action="{{ route('admin.reviews.status', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('تأكيد: رفض هذا التقييم؟');">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="rejected">
                <button class="btn btn-sm btn-secondary" title="رفض" @if($st==='rejected') disabled @endif>
                  <i class="bi bi-x"></i>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted py-4">لا توجد تقييمات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">
    {{ $reviews->withQueryString()->links('vendor.pagination.bootstrap-custom') }}
  </div>
  </div>
@endsection
