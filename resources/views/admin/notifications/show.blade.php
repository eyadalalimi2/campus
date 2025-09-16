@extends('admin.layouts.app')
@section('title','إشعار #'.$notification->id)

@section('content')
<div class="d-flex align-items-center mb-3">
  <h1 class="h4 mb-0">إشعار #{{ $notification->id }}</h1>
  <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary ms-auto">عودة</a>
</div>

<div class="card">
  <div class="card-body">
    <div class="mb-2"><span class="text-muted">العنوان:</span> {{ $notification->title }}</div>
    <div class="mb-2"><span class="text-muted">النص:</span><br>{{ $notification->body }}</div>
    <div class="mb-2"><span class="text-muted">الهدف:</span> {{ $notification->target_type }} @if($notification->target_id) (#{{ $notification->target_id }}) @endif</div>
    @if($notification->action_url)
      <div class="mb-2"><span class="text-muted">الرابط:</span> <a href="{{ $notification->action_url }}" target="_blank">{{ $notification->action_url }}</a></div>
    @endif
    @if($notification->image_url)
      <div class="mb-2"><span class="text-muted">صورة:</span><br><img src="{{ $notification->image_url }}" class="img-fluid rounded" style="max-height:200px"></div>
    @endif
    <div class="text-muted small">أُنشئ: {{ $notification->created_at?->format('Y-m-d H:i') }}</div>
  </div>
</div>
@endsection
