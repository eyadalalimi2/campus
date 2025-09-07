@extends('admin.layouts.app')
@section('title','العناصر التعليمية')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">العناصر التعليمية</h4>
  <a href="{{ route('admin.assets.create') }}" class="btn btn-primary">
    <i class="bi bi-plus"></i> إضافة عنصر
  </a>
</div>

{{-- نموذج الفلاتر --}}
<form class="row g-2 mb-3">
  {{-- الفئة --}}
  <div class="col-md-2">
    <select name="category" class="form-select" onchange="this.form.submit()">
      <option value="">الفئة</option>
      <option value="youtube"        @selected(request('category')==='youtube')>يوتيوب</option>
      <option value="file"           @selected(request('category')==='file')>ملفات</option>
      <option value="reference"      @selected(request('category')==='reference')>مراجع</option>
      <option value="question_bank"  @selected(request('category')==='question_bank')>بنك الأسئلة</option>
      <option value="curriculum"     @selected(request('category')==='curriculum')>المناهج</option>
      <option value="book"           @selected(request('category')==='book')>كتب</option>
    </select>
  </div>

  {{-- المجال (جديد) --}}
  <div class="col-md-2">
    <select name="discipline_id" id="filter-discipline" class="form-select" onchange="this.form.submit()">
      <option value="">المجال</option>
      @foreach($disciplines as $disc)
        <option value="{{ $disc->id }}" @selected(request('discipline_id') == $disc->id)>
          {{ $disc->name }}
        </option>
      @endforeach
    </select>
  </div>

  {{-- البرنامج (جديد) --}}
  <div class="col-md-2">
    <select name="program_id" id="filter-program" class="form-select" onchange="this.form.submit()">
      <option value="">البرنامج</option>
      @foreach($programs as $prog)
        <option value="{{ $prog->id }}"
                data-discipline="{{ $prog->discipline_id }}"
                @selected(request('program_id') == $prog->id)>
          {{ $prog->name }}
        </option>
      @endforeach
    </select>
  </div>

  {{-- المادة --}}
  <div class="col-md-2">
    <select name="material_id" class="form-select" onchange="this.form.submit()">
      <option value="">المادة</option>
      @foreach($materials as $m)
        <option value="{{ $m->id }}" @selected(request('material_id') == $m->id)>{{ $m->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- الجهاز --}}
  <div class="col-md-2">
    <select name="device_id" class="form-select" onchange="this.form.submit()">
      <option value="">الجهاز/المهمة</option>
      @foreach($devices as $d)
        <option value="{{ $d->id }}" @selected(request('device_id') == $d->id)>
          {{ $d->name }} ({{ $d->material->name }})
        </option>
      @endforeach
    </select>
  </div>

  {{-- الدكتور --}}
  <div class="col-md-2">
    <select name="doctor_id" class="form-select" onchange="this.form.submit()">
      <option value="">الدكتور</option>
      @foreach($doctors as $doc)
        <option value="{{ $doc->id }}" @selected(request('doctor_id') == $doc->id)>{{ $doc->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- حالة التفعيل --}}
  <div class="col-md-2">
    <select name="is_active" class="form-select" onchange="this.form.submit()">
      <option value="">التفعيل</option>
      <option value="1" @selected(request('is_active') === '1')>مفعل</option>
      <option value="0" @selected(request('is_active') === '0')>موقوف</option>
    </select>
  </div>

  {{-- حالة النشر (جديد) --}}
  <div class="col-md-2">
    @php $reqStatus = request('status'); @endphp
    <select name="status" class="form-select" onchange="this.form.submit()">
      <option value="">حالة النشر</option>
      <option value="draft"     @selected($reqStatus === 'draft')>مسودة</option>
      <option value="in_review" @selected($reqStatus === 'in_review')>قيد المراجعة</option>
      <option value="published" @selected($reqStatus === 'published')>منشور</option>
      <option value="archived"  @selected($reqStatus === 'archived')>مؤرشف</option>
    </select>
  </div>

  {{-- البحث النصي --}}
  <div class="col-md-3">
    <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="بحث بالعنوان">
  </div>
  <div class="col-md-2">
    <button class="btn btn-outline-secondary w-100">بحث</button>
  </div>
</form>

{{-- جدول الأصول --}}
<div class="table-responsive">
<table class="table table-hover bg-white align-middle">
  <thead class="table-light">
    <tr>
      <th>العنوان</th>
      <th>الفئة</th>
      <th>المجال</th>       {{-- جديد --}}
      <th>البرنامج</th>    {{-- جديد --}}
      <th>المادة</th>
      <th>الجهاز</th>
      <th>الدكتور</th>
      <th>المصدر</th>
      <th>حالة النشر</th>  {{-- جديد --}}
      <th>التفعيل</th>
      <th class="text-center">إجراءات</th>
    </tr>
  </thead>
  <tbody>
    @forelse($assets as $a)
    <tr>
      <td class="fw-semibold">{{ $a->title }}</td>
      <td>
        @switch($a->category)
          @case('youtube')       <span class="badge bg-danger">يوتيوب</span> @break
          @case('file')          <span class="badge bg-secondary">ملف</span> @break
          @case('reference')     <span class="badge bg-info text-dark">مرجع</span> @break
          @case('question_bank') <span class="badge bg-warning text-dark">بنك أسئلة</span> @break
          @case('curriculum')    <span class="badge bg-primary">منهج</span> @break
          @case('book')          <span class="badge bg-success">كتاب</span> @break
        @endswitch
      </td>
      <td class="small text-muted">{{ $a->discipline?->name ?: '—' }}</td>  {{-- المجال --}}
      <td class="small text-muted">{{ $a->program?->name    ?: '—' }}</td>  {{-- البرنامج --}}
      <td class="small text-muted">{{ $a->material?->name    ?: '—' }}</td>
      <td class="small text-muted">{{ $a->device?->name      ?: '—' }}</td>
      <td class="small text-muted">{{ $a->doctor?->name      ?: '—' }}</td>
      <td class="small">
        @if($a->category === 'youtube' && $a->video_url)
          <a href="{{ $a->video_url }}" target="_blank">فتح الفيديو</a>
        @elseif($a->category === 'file' && $a->file_url)
          <a href="{{ $a->file_url }}" target="_blank" download>تحميل الملف</a>
        @elseif($a->external_url)
          <a href="{{ $a->external_url }}" target="_blank">فتح الرابط</a>
        @else
          —
        @endif
      </td>
      <td> {{-- حالة النشر --}}
        @switch($a->status)
          @case('draft')     <span class="badge bg-secondary">مسودة</span> @break
          @case('in_review') <span class="badge bg-info text-dark">قيد المراجعة</span> @break
          @case('published') <span class="badge bg-success">منشور</span> @break
          @case('archived')  <span class="badge bg-dark">مؤرشف</span> @break
          @default           <span class="badge bg-light text-dark">{{ $a->status }}</span>
        @endswitch
      </td>
      <td>
        {!! $a->is_active
            ? '<span class="badge bg-success">مفعل</span>'
            : '<span class="badge bg-secondary">موقوف</span>' !!}
      </td>
      <td class="text-center">
        <a href="{{ route('admin.assets.edit', $a) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
        <form action="{{ route('admin.assets.destroy', $a) }}" method="POST" class="d-inline">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف العنصر؟')">حذف</button>
        </form>
      </td>
    </tr>
    @empty
    <tr>
      <td colspan="11" class="text-center text-muted">لا توجد بيانات.</td>
    </tr>
    @endforelse
  </tbody>
</table>
</div>

{{-- روابط الترقيم --}}
{{ $assets->links('vendor.pagination.bootstrap-custom') }}
@endsection
