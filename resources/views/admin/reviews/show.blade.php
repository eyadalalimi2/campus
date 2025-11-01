@extends('admin.layouts.app')
@section('title','تفاصيل التقييم #'.$review->id)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h5 mb-0">تفاصيل تقييم المستخدم</h1>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-secondary">
      <i class="bi bi-arrow-right-short"></i> عودة للقائمة
    </a>
  </div>
  </div>

<div class="row g-3">
  <div class="col-lg-7">
    <div class="card shadow-sm mb-3">
      <div class="card-body">
        <div class="d-flex align-items-start justify-content-between">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
              <span class="fw-bold">{{ strtoupper(mb_substr(optional($review->user)->name ?? 'U',0,1)) }}</span>
            </div>
            <div>
              <div class="fw-semibold">{{ optional($review->user)->name ?? '—' }}</div>
              <div class="text-muted small d-flex align-items-center gap-2">
                <i class="bi bi-envelope"></i>
                <span>{{ optional($review->user)->email ?? '—' }}</span>
              </div>
            </div>
          </div>
          <div class="text-nowrap">
            <div class="fs-4" title="التقييم: {{ (int)$review->rating }} / 5">
              @for($i=1;$i<=5;$i++)
                @if($i <= (int)$review->rating)
                  <span class="text-warning">★</span>
                @else
                  <span class="text-muted">☆</span>
                @endif
              @endfor
            </div>
            <div class="text-muted small text-center">{{ (int)$review->rating }} / 5</div>
          </div>
        </div>

  <div class="mt-3 d-flex flex-wrap gap-2 align-items-center">
          @php
            $statusMap = [
              'approved' => ['label' => 'معتمد', 'class' => 'success'],
              'pending'  => ['label' => 'بانتظار المراجعة', 'class' => 'warning'],
              'rejected' => ['label' => 'مرفوض', 'class' => 'secondary'],
            ];
            $st = strtolower($review->status ?? 'approved');
            $stCfg = $statusMap[$st] ?? $statusMap['approved'];
          @endphp
          <span class="badge bg-{{ $stCfg['class'] }}">{{ $stCfg['label'] }}</span>
          <span class="badge bg-light text-dark"><i class="bi bi-clock"></i> {{ $review->created_at?->format('Y-m-d H:i') }}</span>
          @if($review->reply_text)
            <span class="badge bg-info text-dark"><i class="bi bi-check2-circle"></i> تم الرد</span>
          @else
            <span class="badge bg-dark"><i class="bi bi-hourglass-split"></i> بانتظار الرد</span>
          @endif

          {{-- أزرار تغيير الحالة السريعة --}}
          <div class="vr d-none d-md-block" style="opacity:.2"></div>
          @php $stCurrent = strtolower($review->status ?? 'approved'); @endphp
          <div class="btn-group btn-group-sm" role="group" aria-label="تحديث الحالة">
            <form action="{{ route('admin.reviews.status', $review) }}" method="POST" onsubmit="return confirm('تأكيد تعيين الحالة: معتمد؟');">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="approved">
              <button class="btn btn-success" @if($stCurrent==='approved') disabled @endif><i class="bi bi-check2"></i> اعتماد</button>
            </form>
            <form action="{{ route('admin.reviews.status', $review) }}" method="POST" onsubmit="return confirm('تأكيد تعيين الحالة: بانتظار؟');">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="pending">
              <button class="btn btn-warning" @if($stCurrent==='pending') disabled @endif><i class="bi bi-hourglass"></i> انتظار</button>
            </form>
            <form action="{{ route('admin.reviews.status', $review) }}" method="POST" onsubmit="return confirm('تأكيد تعيين الحالة: مرفوض؟');">
              @csrf
              @method('PATCH')
              <input type="hidden" name="status" value="rejected">
              <button class="btn btn-secondary" @if($stCurrent==='rejected') disabled @endif><i class="bi bi-x"></i> رفض</button>
            </form>
          </div>
        </div>

        <hr>
        <div class="mb-2 fw-semibold">التعليق</div>
        <blockquote class="blockquote border rounded p-3 bg-light mb-0" style="border-left:4px solid #e5e7eb;">
          <p class="mb-0">{{ $review->comment ?? '—' }}</p>
        </blockquote>
      </div>
    </div>

    <div class="card shadow-sm" id="reply">
      <div class="card-header d-flex justify-content-between align-items-center bg-white">
        <div class="fw-semibold"><i class="bi bi-reply"></i> رد الإدارة</div>
        @if($review->reply_text)
          <span class="text-muted small">آخر تعديل: {{ $review->replied_at?->format('Y-m-d H:i') }} — {{ optional($review->replyAdmin)->name ?? '—' }}</span>
        @endif
      </div>
      <div class="card-body">
        @if(session('status'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
          </div>
        @endif

        @if($review->reply_text)
          <div class="mb-3">
            <div class="border rounded p-3 bg-light">
              {{ $review->reply_text }}
            </div>
          </div>
        @endif

        <form method="POST" action="{{ route('admin.reviews.reply', $review) }}" class="mt-2">
          @csrf
          <div class="mb-3">
            <label class="form-label">نص الرد</label>
            <textarea name="reply_text" rows="6" class="form-control @error('reply_text') is-invalid @enderror" placeholder="اكتب رد الإدارة هنا...">{{ old('reply_text', $review->reply_text) }}</textarea>
            @error('reply_text')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="d-flex gap-2">
            <button class="btn btn-primary" type="submit"><i class="bi bi-send"></i> حفظ الرد</button>
            <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-right"></i> عودة</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card shadow-sm">
      <div class="card-header bg-white fw-semibold">معلومات إضافية</div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="text-muted small">المعرف</span>
            <span class="fw-semibold">#{{ $review->id }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="text-muted small">رقم المستخدم</span>
            <span>{{ $review->user_id }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="text-muted small">الحالة</span>
            <span class="badge bg-{{ $stCfg['class'] }}">{{ $stCfg['label'] }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="text-muted small">تاريخ الإنشاء</span>
            <span>{{ $review->created_at?->format('Y-m-d H:i') }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="text-muted small">تاريخ الرد</span>
            <span>{{ $review->replied_at?->format('Y-m-d H:i') ?? '—' }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="text-muted small">مدير الرد</span>
            <span>{{ optional($review->replyAdmin)->name ?? '—' }}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

@endsection
