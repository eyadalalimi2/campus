@extends('admin.layouts.app')
@section('title','تعديل محتوى')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <div>
    <h4 class="mb-1">تعديل محتوى: {{ $content->title }}</h4>
    <div class="d-flex gap-2 small">
      <span class="badge bg-warning-subtle text-dark border">v{{ $content->version ?? 1 }}</span>

      @switch($content->status)
        @case('draft')     <span class="badge bg-secondary">مسودة</span> @break
        @case('in_review') <span class="badge bg-info text-dark">قيد المراجعة</span> @break
        @case('published') <span class="badge bg-success">منشور</span> @break
        @case('archived')  <span class="badge bg-dark">مؤرشف</span> @break
        @default           <span class="badge bg-light text-dark">{{ $content->status }}</span>
      @endswitch

      {!! $content->is_active
        ? '<span class="badge bg-success">مفعل</span>'
        : '<span class="badge bg-secondary">موقوف</span>' !!}

      @if($content->published_at)
        <span class="text-muted">
          <i class="bi bi-clock"></i> {{ $content->published_at->format('Y-m-d') }}
          @if($content->publishedBy?->name) — {{ $content->publishedBy->name }} @endif
        </span>
      @endif
    </div>
  </div>

  <div>
    <a href="{{ route('admin.contents.index') }}" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-right-circle"></i> رجوع
    </a>
  </div>
</div>

<form action="{{ route('admin.contents.update',$content) }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf @method('PUT')

  @include('admin.contents.form', ['content'=>$content])

  <div class="mt-3">
    <label class="form-label">مذكرة الإصدار (اختياري)</label>
    <textarea name="changelog" rows="3" class="form-control"
      placeholder="وصف مختصر لما تغيّر في هذا الإصدار (يظهر للمراجعة الداخلية)">{{ old('changelog', $content->changelog ?? '') }}</textarea>
    <div class="form-text">
      * سيتم <strong>رفع رقم الإصدار تلقائياً</strong> عند تغيير الملف أو رابط المصدر. يمكنك توثيق التغييرات هنا.
    </div>
  </div>

  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary"><i class="bi bi-save"></i> تحديث</button>
    <a href="{{ route('admin.contents.index') }}" class="btn btn-link">إلغاء</a>
  </div>
</form>
@endsection
