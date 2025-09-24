@extends('admin.layouts.app')
@section('title','عرض مورد')
@section('content')
<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">
          <i class="bi bi-eye"></i> تفاصيل المورد
        </div>
        <div class="card-body">
          <dl class="row">
            <dt class="col-sm-4">العنوان</dt>
            <dd class="col-sm-8">{{ $item->title }}</dd>
            <dt class="col-sm-4">الوصف</dt>
            <dd class="col-sm-8">{{ $item->description }}</dd>
            <dt class="col-sm-4">نوع المورد</dt>
            <dd class="col-sm-8">
              @if($item->type==='YOUTUBE') يوتيوب
              @elseif($item->type==='BOOK') كتاب
              @elseif($item->type==='SUMMARY') ملخص
              @elseif($item->type==='REFERENCE') مرجع علمي
              @elseif($item->type==='QUESTION_BANK') بنك أسئلة
              @else {{ $item->type }}
              @endif
            </dd>
            <dt class="col-sm-4">المسار الدراسي</dt>
            <dd class="col-sm-8">@if($item->track==='BASIC') أساسي @elseif($item->track==='CLINICAL') إكلينيكي @else {{ $item->track }} @endif</dd>
            <dt class="col-sm-4">المادة الدراسية</dt>
            <dd class="col-sm-8">{{ optional($item->subject)->name_ar }}</dd>
            <dt class="col-sm-4">الجهاز/التخصص</dt>
            <dd class="col-sm-8">{{ optional($item->system)->name_ar }}</dd>
            <dt class="col-sm-4">الدكتور</dt>
            <dd class="col-sm-8">{{ optional($item->doctor)->name }}</dd>
            <dt class="col-sm-4">الحالة</dt>
            <dd class="col-sm-8">
              @if($item->status==='PUBLISHED') <span class="badge bg-success">منشور</span>
              @elseif($item->status==='DRAFT') <span class="badge bg-warning text-dark">مسودة</span>
              @elseif($item->status==='ARCHIVED') <span class="badge bg-secondary">مؤرشف</span>
              @else {{ $item->status }}
              @endif
            </dd>
            <dt class="col-sm-4">اللغة</dt>
            <dd class="col-sm-8">{{ $item->language }}</dd>
            <dt class="col-sm-4">السنة</dt>
            <dd class="col-sm-8">{{ $item->year }}</dd>
            <dt class="col-sm-4">المستوى</dt>
            <dd class="col-sm-8">{{ $item->level }}</dd>
            <dt class="col-sm-4">الرخصة</dt>
            <dd class="col-sm-8">{{ $item->license }}</dd>
            <dt class="col-sm-4">الظهور</dt>
            <dd class="col-sm-8">{{ $item->visibility }}</dd>
            <dt class="col-sm-4">فعال؟</dt>
            <dd class="col-sm-8">{!! $item->is_active ? '<span class="badge bg-success">نعم</span>' : '<span class="badge bg-danger">لا</span>' !!}</dd>
          </dl>

          @if($item->type==='YOUTUBE' && $item->youtubeMeta)
          <hr>
          <h5 class="mb-3"><i class="bi bi-youtube"></i> بيانات يوتيوب</h5>
          <ul>
            <li>channel_id: {{ $item->youtubeMeta->channel_id }}</li>
            <li>video_id: {{ $item->youtubeMeta->video_id }}</li>
            <li>playlist_id: {{ $item->youtubeMeta->playlist_id }}</li>
          </ul>
          @endif

          @if($item->type==='REFERENCE' && $item->reference)
          <hr>
          <h5 class="mb-3"><i class="bi bi-journal-text"></i> بيانات المرجع العلمي</h5>
          <ul>
            <li>النص المرجعي: {{ $item->reference->citation_text }}</li>
            <li>DOI: {{ $item->reference->doi }}</li>
            <li>ISBN: {{ $item->reference->isbn }}</li>
            <li>PMID: {{ $item->reference->pmid }}</li>
            <li>الناشر: {{ $item->reference->publisher }}</li>
            <li>الطبعة: {{ $item->reference->edition }}</li>
          </ul>
          @endif

          <hr>
          <h5 class="mb-3"><i class="bi bi-file-earmark-arrow-up"></i> الملفات المرفقة</h5>
          <ul>
            @foreach($item->files as $f)
              <li><a href="{{ $f->cdn_url }}" target="_blank">رابط الملف</a> ({{ number_format($f->bytes) }} بايت)</li>
            @endforeach
            @if($item->files->isEmpty())
              <li class="text-muted">لا توجد ملفات مرفقة</li>
            @endif
          </ul>

          <hr>
          <h5 class="mb-3"><i class="bi bi-building"></i> الجامعات المخوّلة</h5>
          <ul>
            @foreach($item->universities as $u)
              <li>{{ $u->name }}</li>
            @endforeach
            @if($item->universities->isEmpty())
              <li class="text-muted">لا توجد جامعات مرتبطة</li>
            @endif
          </ul>

          <a href="{{ route('medical.resources.edit',$item) }}" class="btn btn-warning mt-3"><i class="bi bi-pencil"></i> تعديل</a>
          <a href="{{ route('medical.resources.index') }}" class="btn btn-secondary mt-3"><i class="bi bi-arrow-right"></i> رجوع للقائمة</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
