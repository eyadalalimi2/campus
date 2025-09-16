@extends('admin.layouts.app')
@section('title','عرض شكوى')

@section('content')
<h1 class="h4 mb-3">تفاصيل الشكوى #{{ $complaint->id }}</h1>

@if(session('status'))
  <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="row g-3">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body">
        <dl class="row mb-0">
          <dt class="col-sm-3">المستخدم</dt>
          <dd class="col-sm-9">
            @if($complaint->user)
              <div>{{ $complaint->user->name }}</div>
              <div class="text-muted small">{{ $complaint->user->email }} @if($complaint->user->phone) — {{ $complaint->user->phone }} @endif</div>
            @else
              —
            @endif
          </dd>

          <dt class="col-sm-3">الموضوع</dt>
          <dd class="col-sm-9">{{ $complaint->subject ?? '—' }}</dd>

          <dt class="col-sm-3">النص</dt>
          <dd class="col-sm-9"><pre class="mb-0" style="white-space: pre-wrap;">{{ $complaint->body ?? '—' }}</pre></dd>

          <dt class="col-sm-3">النوع</dt>
          <dd class="col-sm-9"><span class="badge bg-info-subtle text-dark">{{ $complaint->type ?? '—' }}</span></dd>

          <dt class="col-sm-3">الخطورة</dt>
          <dd class="col-sm-9"><span class="badge bg-secondary">{{ $complaint->severity ?? '—' }}</span></dd>

          <dt class="col-sm-3">الحالة</dt>
          <dd class="col-sm-9"><span class="badge bg-primary">{{ $complaint->status ?? '—' }}</span></dd>

          <dt class="col-sm-3">المُعيّن</dt>
          <dd class="col-sm-9">{{ optional($complaint->assignee)->name ?? '—' }}</dd>

          <dt class="col-sm-3">الهدف</dt>
          <dd class="col-sm-9">
            {{ $complaint->target_type ?? '—' }}
            @if($complaint->target_id) <span class="text-muted">#{{ $complaint->target_id }}</span> @endif
          </dd>

          <dt class="col-sm-3">مرفق</dt>
          <dd class="col-sm-9">
            @if($complaint->attachment_path)
              <a class="btn btn-sm btn-outline-secondary" target="_blank"
                 href="{{ \Illuminate\Support\Str::startsWith($complaint->attachment_path, ['http://','https://']) ? $complaint->attachment_path : \Illuminate\Support\Facades\Storage::url($complaint->attachment_path) }}">
                عرض/تنزيل
              </a>
            @else
              —
            @endif
          </dd>

          <dt class="col-sm-3">أُنشئ</dt>
          <dd class="col-sm-9">{{ $complaint->created_at?->format('Y-m-d H:i') ?? '—' }}</dd>

          <dt class="col-sm-3">أُغلق</dt>
          <dd class="col-sm-9">{{ $complaint->closed_at?->format('Y-m-d H:i') ?? '—' }}</dd>
        </dl>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">تحديث الشكوى</div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.complaints.update', $complaint) }}">
          @csrf
          @method('PATCH')

          <div class="mb-3">
            <label class="form-label">الحالة</label>
            <select name="status" class="form-select">
              @foreach(['open','triaged','in_progress','resolved','rejected','closed'] as $s)
                <option value="{{ $s }}" @selected(old('status',$complaint->status)===$s)>{{ $s }}</option>
              @endforeach
            </select>
            @error('status') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">الخطورة</label>
            <select name="severity" class="form-select">
              @foreach(['low','medium','high','critical'] as $sev)
                <option value="{{ $sev }}" @selected(old('severity',$complaint->severity)===$sev)>{{ $sev }}</option>
              @endforeach
            </select>
            @error('severity') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">المُعيّن</label>
            <select name="assigned_admin_id" class="form-select">
              <option value="">— لا أحد —</option>
              @foreach($admins as $a)
                <option value="{{ $a->id }}" @selected(old('assigned_admin_id',$complaint->assigned_admin_id)===$a->id)>{{ $a->name }}</option>
              @endforeach
            </select>
            @error('assigned_admin_id') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">ملاحظة (اختياري)</label>
            <textarea name="note" class="form-control" rows="3" placeholder="أضف ملاحظة داخلية...">{{ old('note') }}</textarea>
          </div>

          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="close_now" value="1" id="close_now"
                   @checked(old('close_now', $complaint->status==='closed'))>
            <label class="form-check-label" for="close_now">الإغلاق الآن (ضبط closed_at)</label>
          </div>

          <div class="d-flex gap-2">
            <button class="btn btn-primary w-100">حفظ</button>
            <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-secondary w-100">رجوع</a>
          </div>
        </form>
      </div>
    </div>

    <form method="POST" action="{{ route('admin.complaints.destroy', $complaint) }}" class="mt-3"
          onsubmit="return confirm('تأكيد الحذف؟ سيتم الحذف كـ Soft Delete.');">
      @csrf @method('DELETE')
      <button class="btn btn-outline-danger w-100">حذف (Soft)</button>
    </form>
  </div>
</div>
@endsection
