
@extends('admin.layouts.app')
@section('title','تعديل مورد')
@section('content')
<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
          <i class="bi bi-collection"></i> تعديل مورد
        </div>
        <div class="card-body">
          <form method="post" action="{{ route('medical.resources.update',$item) }}">
            @csrf @method('PUT')
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">نوع المورد</label>
                <select name="type" class="form-select" required>
                  <option value="YOUTUBE" {{ $item->type==='YOUTUBE'?'selected':'' }}>يوتيوب</option>
                  <option value="BOOK" {{ $item->type==='BOOK'?'selected':'' }}>كتاب</option>
                  <option value="SUMMARY" {{ $item->type==='SUMMARY'?'selected':'' }}>ملخص</option>
                  <option value="REFERENCE" {{ $item->type==='REFERENCE'?'selected':'' }}>مرجع علمي</option>
                  <option value="QUESTION_BANK" {{ $item->type==='QUESTION_BANK'?'selected':'' }}>بنك أسئلة</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">المسار الدراسي</label>
                <select name="track" class="form-select" required>
                  <option value="BASIC" {{ $item->track==='BASIC'?'selected':'' }}>أساسي</option>
                  <option value="CLINICAL" {{ $item->track==='CLINICAL'?'selected':'' }}>إكلينيكي</option>
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">المادة الدراسية</label>
                <select name="subject_id" class="form-select" required>
                  @foreach($subjects as $s)
                    <option value="{{ $s->id }}" {{ $item->subject_id==$s->id?'selected':'' }}>{{ $s->code }} — {{ $s->name_ar }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">الجهاز (اختياري)</label>
                <select name="system_id" class="form-select">
                  <option value="">—</option>
                  @foreach($systems as $s)
                    <option value="{{ $s->id }}" {{ $item->system_id==$s->id?'selected':'' }}>{{ $s->name_ar }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-4">
                <label class="form-label">الدكتور (اختياري)</label>
                <select name="doctor_id" class="form-select">
                  <option value="">—</option>
                  @foreach($doctors as $d)
                    <option value="{{ $d->id }}" {{ $item->doctor_id==$d->id?'selected':'' }}>{{ $d->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label">العنوان</label>
                <input name="title" class="form-control" value="{{ $item->title }}" required>
              </div>
              <div class="col-md-6">
                <label class="form-label">الوصف</label>
                <textarea name="description" class="form-control">{{ $item->description }}</textarea>
              </div>
              <div class="col-md-3">
                <label class="form-label">اللغة</label>
                <input name="language" value="{{ $item->language }}" class="form-control">
              </div>
              <div class="col-md-3">
                <label class="form-label">السنة</label>
                <input type="number" name="year" min="1900" max="2100" class="form-control" value="{{ $item->year }}">
              </div>
              <div class="col-md-3">
                <label class="form-label">المستوى</label>
                <select name="level" class="form-select">
                  <option value="basic" {{ $item->level=='basic'?'selected':'' }}>أساسي</option>
                  <option value="advanced" {{ $item->level=='advanced'?'selected':'' }}>متقدم</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">الرخصة</label>
                <select name="license" class="form-select">
                  <option value="LINK_ONLY" {{ $item->license=='LINK_ONLY'?'selected':'' }}>رابط فقط</option>
                  <option value="OPEN" {{ $item->license=='OPEN'?'selected':'' }}>مفتوحة</option>
                  <option value="RESTRICTED" {{ $item->license=='RESTRICTED'?'selected':'' }}>مقيدة</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">الظهور</label>
                <select name="visibility" class="form-select">
                  <option value="PUBLIC" {{ $item->visibility=='PUBLIC'?'selected':'' }}>عام</option>
                  <option value="RESTRICTED" {{ $item->visibility=='RESTRICTED'?'selected':'' }}>مقيد</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">الحالة</label>
                <select name="status" class="form-select">
                  <option value="PUBLISHED" {{ $item->status=='PUBLISHED'?'selected':'' }}>منشور</option>
                  <option value="DRAFT" {{ $item->status=='DRAFT'?'selected':'' }}>مسودة</option>
                  <option value="ARCHIVED" {{ $item->status=='ARCHIVED'?'selected':'' }}>مؤرشف</option>
                </select>
              </div>
            </div>
            <button class="btn btn-success mt-3"><i class="bi bi-save"></i> تحديث</button>
          </form>
          <script>
            // عند إلغاء التفعيل، احذف الحقل من الفورم حتى لا يتم إرساله
            document.addEventListener('DOMContentLoaded', function() {
              var checkbox = document.getElementById('is_active');
              var form = checkbox.closest('form');
              checkbox.addEventListener('change', function() {
                if (!checkbox.checked) {
                  checkbox.removeAttribute('name');
                } else {
                  checkbox.setAttribute('name', 'is_active');
                }
              });
            });
          </script>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@if($item->type === 'YOUTUBE')
<div class="container mb-4">
  <div class="card border-info">
    <div class="card-header bg-info text-white"><i class="bi bi-youtube"></i> بيانات يوتيوب</div>
    <div class="card-body">
      <form method="post" action="{{ route('medical.resources.update',$item) }}">
        @csrf @method('PUT')
        <input type="hidden" name="type" value="YOUTUBE">
        <input type="hidden" name="track" value="{{ $item->track }}">
        <input type="hidden" name="subject_id" value="{{ $item->subject_id }}">
        <div class="mb-3">
          <label class="form-label">معرّف القناة (channel_id)</label>
          <input class="form-control" name="channel_id" value="{{ $item->youtubeMeta->channel_id ?? '' }}">
          <div class="form-text">أدخل معرّف قناة اليوتيوب المرتبطة بالمورد.</div>
        </div>
        <div class="mb-3">
          <label class="form-label">معرّف الفيديو (video_id)</label>
          <input class="form-control" name="video_id" value="{{ $item->youtubeMeta->video_id ?? '' }}">
          <div class="form-text">أدخل معرّف الفيديو من يوتيوب.</div>
        </div>
        <div class="mb-3">
          <label class="form-label">معرّف قائمة التشغيل (playlist_id)</label>
          <input class="form-control" name="playlist_id" value="{{ $item->youtubeMeta->playlist_id ?? '' }}">
          <div class="form-text">(اختياري) أدخل معرّف قائمة التشغيل إذا كان المورد ضمن قائمة تشغيل.</div>
        </div>
        <button class="btn btn-danger"><i class="bi bi-youtube"></i> تحديث بيانات يوتيوب</button>
      </form>
    </div>
  </div>
</div>
@endif

@if($item->type === 'REFERENCE')
<div class="container mb-4">
  <div class="card border-secondary">
    <div class="card-header bg-secondary text-white"><i class="bi bi-journal-text"></i> بيانات المرجع العلمي</div>
    <div class="card-body">
      <form method="post" action="{{ route('medical.resources.update',$item) }}">
        @csrf @method('PUT')
        <input type="hidden" name="type" value="REFERENCE">
        <input type="hidden" name="track" value="{{ $item->track }}">
        <input type="hidden" name="subject_id" value="{{ $item->subject_id }}">
        <div class="mb-3">
          <label class="form-label">النص المرجعي (citation_text)</label>
          <textarea class="form-control" name="citation_text">{{ $item->reference->citation_text ?? '' }}</textarea>
          <div class="form-text">أدخل النص المرجعي الكامل كما هو منشور في المصدر.</div>
        </div>
        <div class="mb-3">
          <label class="form-label">DOI</label>
          <input class="form-control" name="doi" value="{{ $item->reference->doi ?? '' }}">
          <div class="form-text">معرّف الكائن الرقمي (Digital Object Identifier) إن وجد.</div>
        </div>
        <div class="mb-3">
          <label class="form-label">ISBN</label>
          <input class="form-control" name="isbn" value="{{ $item->reference->isbn ?? '' }}">
          <div class="form-text">الرقم الدولي المعياري للكتاب (إن وجد).</div>
        </div>
        <div class="mb-3">
          <label class="form-label">PMID</label>
          <input class="form-control" name="pmid" value="{{ $item->reference->pmid ?? '' }}">
          <div class="form-text">معرّف PubMed (إن وجد).</div>
        </div>
        <div class="mb-3">
          <label class="form-label">الناشر</label>
          <input class="form-control" name="publisher" value="{{ $item->reference->publisher ?? '' }}">
        </div>
        <div class="mb-3">
          <label class="form-label">الطبعة</label>
          <input class="form-control" name="edition" value="{{ $item->reference->edition ?? '' }}">
        </div>
        <button class="btn btn-secondary"><i class="bi bi-journal-text"></i> تحديث بيانات المرجع</button>
      </form>
    </div>
  </div>
</div>
@endif

<div class="container mb-4">
  <div class="card border-success">
    <div class="card-header bg-success text-white"><i class="bi bi-file-earmark-arrow-up"></i> إدارة الملفات المرفقة</div>
    <div class="card-body">
      <form method="post" action="{{ route('medical.resources.files.store',$item) }}" enctype="multipart/form-data" class="row g-3 align-items-center mb-3">
        @csrf
        <div class="col-auto">
          <label for="file" class="form-label">رفع ملف جديد</label>
          <input type="file" class="form-control" name="file" id="file" required>
        </div>
        <div class="col-auto">
          <div class="form-check mt-4">
            <input type="hidden" name="download_allowed" value="0">
            <input class="form-check-input" type="checkbox" name="download_allowed" id="download_allowed" value="1">
            <label class="form-check-label" for="download_allowed">السماح بتنزيل الملف</label>
          </div>
        </div>
        <div class="col-auto mt-4">
          <button class="btn btn-success"><i class="bi bi-upload"></i> رفع</button>
        </div>
      </form>
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>رابط الملف</th>
              <th>الحجم (بايت)</th>
              <th>خيارات</th>
            </tr>
          </thead>
          <tbody>
            @foreach($item->files as $f)
            <tr>
              <td><a href="{{ $f->cdn_url }}" target="_blank"><i class="bi bi-link-45deg"></i> عرض الملف</a></td>
              <td>{{ number_format($f->bytes) }}</td>
              <td>
                <form method="post" action="{{ route('medical.resources.files.destroy',[$item,$f]) }}" style="display:inline">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> حذف</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="container mb-4">
  <div class="card border-warning">
    <div class="card-header bg-warning"><i class="bi bi-building"></i> الجامعات المخوّلة</div>
    <div class="card-body">
      <form method="post" action="{{ route('medical.resources.universities.attach',$item) }}" class="row g-3 align-items-center mb-3">
        @csrf
        <div class="col-auto">
          <label for="university_id" class="form-label">إضافة جامعة</label>
          <select class="form-select" name="university_id" id="university_id">
            @foreach($universities as $u)
              <option value="{{ $u->id }}">{{ $u->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-auto mt-4">
          <button class="btn btn-warning"><i class="bi bi-plus-circle"></i> إضافة</button>
        </div>
      </form>
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
          <thead class="table-light">
            <tr>
              <th>اسم الجامعة</th>
              <th>خيارات</th>
            </tr>
          </thead>
          <tbody>
            @foreach($item->universities as $u)
            <tr>
              <td>{{ $u->name }}</td>
              <td>
                <form method="post" action="{{ route('medical.resources.universities.detach',[$item,$u]) }}" style="display:inline">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="bi bi-x-circle"></i> إزالة</button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
