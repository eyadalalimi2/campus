
@extends('admin.layouts.app')
@section('title','مورد جديد')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-8 col-lg-7">
    <div class="card">
      <div class="card-header"><i class="bi bi-collection"></i> إضافة مورد جديد</div>
      <div class="card-body">
        <form method="post" action="{{ route('medical.resources.store') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">النوع</label>
              <select name="type" class="form-select" required>
                <option>YOUTUBE</option><option>BOOK</option><option>SUMMARY</option>
                <option>REFERENCE</option><option>QUESTION_BANK</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">المسار</label>
              <select name="track" class="form-select" required><option>BASIC</option><option>CLINICAL</option></select>
            </div>
            <div class="col-md-4">
              <label class="form-label">المادة</label>
              <select name="subject_id" class="form-select" required>@foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->code }} — {{ $s->name_ar }}</option>@endforeach</select>
            </div>
            <div class="col-md-4">
              <label class="form-label">الجهاز (اختياري)</label>
              <select name="system_id" class="form-select"><option value="">—</option>@foreach($systems as $s)<option value="{{ $s->id }}">{{ $s->name_ar }}</option>@endforeach</select>
            </div>
            <div class="col-md-4">
              <label class="form-label">الدكتور (اختياري)</label>
              <select name="doctor_id" class="form-select"><option value="">—</option>@foreach($doctors as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach</select>
            </div>
            <div class="col-md-6">
              <label class="form-label">العنوان</label>
              <input name="title" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">الوصف</label>
              <textarea name="description" class="form-control"></textarea>
            </div>
            <div class="col-md-3">
              <label class="form-label">اللغة</label>
              <input name="language" value="ar" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label">السنة</label>
              <input type="number" name="year" min="1900" max="2100" class="form-control">
            </div>
            <div class="col-md-3">
              <label class="form-label">المستوى</label>
              <select name="level" class="form-select"><option value="basic">basic</option><option value="advanced">advanced</option></select>
            </div>
            <div class="col-md-3">
              <label class="form-label">الرخصة</label>
              <select name="license" class="form-select"><option>LINK_ONLY</option><option>OPEN</option><option>RESTRICTED</option></select>
            </div>
            <div class="col-md-3">
              <label class="form-label">الظهور</label>
              <select name="visibility" class="form-select"><option>PUBLIC</option><option>RESTRICTED</option></select>
            </div>
            <div class="col-md-3">
              <label class="form-label">الحالة</label>
              <select name="status" class="form-select"><option>PUBLISHED</option><option>DRAFT</option><option>ARCHIVED</option></select>
            </div>
          </div>
          <hr>
          <h5 class="mt-4">حقول إضافية للأنواع:</h5>
          <div class="alert alert-info">
            <p class="mb-1">YOUTUBE: channel_id, video_id, playlist_id</p>
            <p class="mb-0">REFERENCE: citation_text, doi, isbn, pmid, publisher, edition</p>
          </div>
          <button class="btn btn-primary mt-3"><i class="bi bi-save"></i> حفظ</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
