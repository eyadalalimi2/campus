@extends('student.layouts.app')
@section('title','لوحة الطالب')

@section('content')
<h5 class="mb-3">مرحباً، {{ $user->name }} 👋</h5>

<div class="row g-3">
  <div class="col-md-4">
    <div class="card kpi-card grad-a p-3 h-100">
      <div class="icon"><i class="bi bi-journal-text"></i></div>
      <div class="small">موادك المتاحة</div>
      <div class="value">{{ $stats['materials'] }}</div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card kpi-card grad-b p-3 h-100">
      <div class="icon"><i class="bi bi-collection"></i></div>
      <div class="small">أحدث محتوى</div>
      <div class="value">{{ $stats['contents'] }}</div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card kpi-card grad-c p-3 h-100">
      <div class="icon"><i class="bi bi-cpu"></i></div>
      <div class="small">الأجهزة/المهام المرتبطة</div>
      <div class="value">{{ $stats['devices'] }}</div>
    </div>
  </div>
</div>

<div class="row g-3 mt-1">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header bg-white"><strong>موادّي</strong></div>
      <div class="list-group list-group-flush">
        @forelse($materials as $m)
          <div class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold">{{ $m->name }}</div>
              <div class="small text-muted">
                @if($m->scope==='global') عام @else خاص بجامعتك @endif
                @if($m->level) — مستوى {{ $m->level }} @endif
                @if($m->term) — {{ ['first'=>'الأول','second'=>'الثاني','summer'=>'الصيفي'][$m->term] ?? '' }} @endif
              </div>
            </div>
            <i class="bi bi-chevron-left"></i>
          </div>
        @empty
          <div class="list-group-item text-muted text-center">لا توجد مواد متاحة.</div>
        @endforelse
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card">
      <div class="card-header bg-white"><strong>أحدث المحتوى</strong></div>
      <div class="list-group list-group-flush">
        @forelse($latestContents as $c)
          <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
             href="{{ $c->type==='file' ? ($c->file_url ?? '#') : ($c->source_url ?? '#') }}" target="_blank">
            <div>
              <div class="fw-semibold">{{ $c->title }}</div>
              <div class="small text-muted">
                @if($c->type==='file') ملف
                @elseif($c->type==='video') فيديو
                @else رابط @endif
                — {{ $c->created_at?->format('Y-m-d') }}
              </div>
            </div>
            <i class="bi bi-box-arrow-up-right"></i>
          </a>
        @empty
          <div class="list-group-item text-muted text-center">لا يوجد محتوى حديث.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>

<div class="card mt-3">
  <div class="card-header bg-white"><strong>الأجهزة/المهام المرتبطة</strong></div>
  <div class="list-group list-group-flush">
    @forelse($devices as $d)
      <div class="list-group-item">
        <div class="fw-semibold">{{ $d->name }}</div>
        <div class="small text-muted">{{ $d->material?->name }}</div>
      </div>
    @empty
      <div class="list-group-item text-muted text-center">لا توجد أجهزة/مهام حالياً.</div>
    @endforelse
  </div>
</div>
@endsection
