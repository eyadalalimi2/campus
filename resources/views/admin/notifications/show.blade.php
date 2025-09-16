@extends('admin.layouts.app')
@section('title','عرض إشعار')

@section('content')
@php
  $typeLabels = [
    'content_created' => 'تم إنشاء محتوى',
    'content_updated' => 'تم تحديث محتوى',
    'content_deleted' => 'تم حذف محتوى',
    'asset_created'   => 'تم إنشاء مادة تعليمية',
    'asset_updated'   => 'تم تحديث مادة تعليمية',
    'asset_deleted'   => 'تم حذف مادة تعليمية',
    'system'          => 'نظام',
    'other'           => 'أخرى',
  ];
  $data = is_array($n->data) ? $n->data : ($n->data ? json_decode($n->data, true) : []);
  $actionUrl = $data['action_url'] ?? null;
  $imageUrl  = $data['image_url']  ?? null;
  $typeLabel = $typeLabels[$n->type] ?? $n->type;
@endphp

<h1 class="h4 mb-3">تفاصيل الإشعار #{{ $n->id }}</h1>

<div class="mb-3 d-flex gap-2">
  <a href="{{ route('admin.notifications.index') }}" class="btn btn-outline-secondary">عودة للقائمة</a>
  <form method="POST" action="{{ route('admin.notifications.destroy',$n) }}" onsubmit="return confirm('حذف الإشعار؟');">
    @csrf @method('DELETE')
    <button class="btn btn-outline-danger">حذف</button>
  </form>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="mb-1">{{ $n->title }}</h5>
        <div class="text-muted small mb-3">
          النوع: <span class="badge bg-info-subtle text-dark">{{ $typeLabel }}</span> ·
          الهدف: {{ $n->target_type ?? '—' }} @if($n->target_id) #{{ $n->target_id }} @endif ·
          الحالة:
          @if($n->read_at)
            <span class="badge bg-success">مقروء</span>
          @else
            <span class="badge bg-secondary">غير مقروء</span>
          @endif
          · أُنشئ: {{ optional($n->created_at)->format('Y-m-d H:i') }}
        </div>

        <p class="mb-3" style="white-space:pre-wrap">{{ $n->body ?? '—' }}</p>

        @if($actionUrl)
          <a href="{{ $actionUrl }}" target="_blank" class="btn btn-primary">فتح الرابط</a>
        @endif
      </div>
    </div>

    <div class="card">
      <div class="card-header">البيانات الإضافية</div>
      <div class="card-body">
        <dl class="row mb-0">
          <dt class="col-sm-3">content_id</dt>
          <dd class="col-sm-9">{{ $n->content_id ?? '—' }}</dd>

          <dt class="col-sm-3">asset_id</dt>
          <dd class="col-sm-9">{{ $n->asset_id ?? '—' }}</dd>

          <dt class="col-sm-3">read_at</dt>
          <dd class="col-sm-9">{{ optional($n->read_at)->format('Y-m-d H:i') ?? '—' }}</dd>

          <dt class="col-sm-3">data (JSON)</dt>
          <dd class="col-sm-9">
            <pre class="mb-0" style="white-space:pre-wrap">{{ $n->data ? (is_array($n->data) ? json_encode($n->data, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) : $n->data) : '—' }}</pre>
          </dd>
        </dl>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card mb-3">
      <div class="card-header">المستخدم</div>
      <div class="card-body">
        <div class="fw-semibold">{{ $n->user?->name ?? '—' }}</div>
        <div class="text-muted small">{{ $n->user?->email ?? '—' }}</div>
        <div class="text-muted small">#{{ $n->user_id }}</div>
      </div>
    </div>

    @if($imageUrl)
      <div class="card">
        <div class="card-header">الصورة</div>
        <div class="card-body text-center">
          <img src="{{ $imageUrl }}" alt="notification image" class="img-fluid rounded">
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
