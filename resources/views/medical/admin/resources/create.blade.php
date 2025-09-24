
@extends('admin.layouts.app')
@section('title','مورد جديد')
@section('content')
<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
          <i class="bi bi-collection"></i> إضافة مورد جديد
        </div>
        <div class="card-body">
          <form method="post" action="{{ route('medical.resources.store') }}">
            @csrf
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">نوع المورد</label>
                <select name="type" class="form-select" required>
                  <option value="YOUTUBE">يوتيوب</option>
                  <option value="BOOK">كتاب</option>
                  <option value="SUMMARY">ملخص</option>
                  <option value="REFERENCE">مرجع علمي</option>
                  <option value="QUESTION_BANK">بنك أسئلة</option>
                </select>
                <div class="form-text">اختر نوع المورد (فيديو، كتاب، ملخص...)</div>
              </div>
              <div class="col-md-4">
                <label class="form-label">المسار الدراسي</label>
                <select name="track" class="form-select" required>
                  <option value="BASIC">أساسي</option>
                  <option value="CLINICAL">إكلينيكي</option>
                </select>
                <div class="form-text">حدد المسار الدراسي المناسب للمورد.</div>
              </div>
              <div class="col-md-4">
                <label class="form-label">المادة الدراسية</label>
                <select name="subject_id" class="form-select" required>
                  @foreach($subjects as $s)
                    <option value="{{ $s->id }}">{{ $s->code }} — {{ $s->name_ar }}</option>
                  @endforeach
                </select>
                <div class="form-text">اختر المادة المرتبطة بالمورد.</div>
              </div>
              <div class="col-md-4">
                <label class="form-label">الجهاز (اختياري)</label>
                <select name="system_id" class="form-select">
                  <option value="">—</option>
                  @foreach($systems as $s)
                    <option value="{{ $s->id }}">{{ $s->name_ar }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">الدكتور (اختياري)</label>
                <select name="doctor_id" class="form-select">
                  <option value="">—</option>
                  @foreach($doctors as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">العنوان</label>
                <input name="title" class="form-control" required>
                <div class="form-text">أدخل عنوانًا واضحًا للمورد.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label">الوصف</label>
                <textarea name="description" class="form-control"></textarea>
                <div class="form-text">يمكنك كتابة وصف مختصر عن المورد.</div>
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
                <select name="level" class="form-select">
                  <option value="basic">أساسي</option>
                  <option value="advanced">متقدم</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">الرخصة</label>
                <select name="license" class="form-select">
                  <option value="LINK_ONLY">رابط فقط</option>
                  <option value="OPEN">مفتوحة</option>
                  <option value="RESTRICTED">مقيدة</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">الظهور</label>
                <select name="visibility" class="form-select">
                  <option value="PUBLIC">عام</option>
                  <option value="RESTRICTED">مقيد</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select">
                  <option value="PUBLISHED">منشور</option>
                  <option value="DRAFT">مسودة</option>
                  <option value="ARCHIVED">مؤرشف</option>
                </select>
              </div>
            </div>
            <hr>
            <h5 class="mt-4">حقول إضافية حسب النوع:</h5>
            <div class="alert alert-info">
              <p class="mb-1"><b>يوتيوب:</b> channel_id, video_id, playlist_id</p>
              <p class="mb-0"><b>مرجع علمي:</b> citation_text, doi, isbn, pmid, publisher, edition</p>
            </div>
            <div class="form-check mb-3">
              <input type="hidden" name="is_active" value="0">
              <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" checked>
              <label class="form-check-label" for="is_active">فعال (يكون المورد ظاهرًا للطلاب بشكل افتراضي)</label>
            </div>
            <button class="btn btn-primary mt-3"><i class="bi bi-save"></i> حفظ</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
