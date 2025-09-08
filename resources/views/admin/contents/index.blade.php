@extends('admin.layouts.app')
@section('title', 'إدارة المحتوى')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">المحتوى</h4>
  <a href="{{ route('admin.contents.create') }}" class="btn btn-primary">
    <i class="bi bi-plus"></i> إضافة محتوى
  </a>
</div>

{{-- نموذج الفلاتر --}}
<form class="row g-2 mb-3" method="GET">
  {{-- النوع --}}
  <div class="col-md-2">
    <select name="type" id="flt_type" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الأنواع —</option>
      <option value="file"  @selected(request('type') === 'file')>ملف</option>
      <option value="video" @selected(request('type') === 'video')>فيديو</option>
      <option value="link"  @selected(request('type') === 'link')>رابط</option>
    </select>
  </div>

  {{-- التفعيل --}}
  <div class="col-md-2">
    <select name="is_active" class="form-select" onchange="this.form.submit()">
      <option value="">— التفعيل —</option>
      <option value="1" @selected(request('is_active') === '1')>مفعل</option>
      <option value="0" @selected(request('is_active') === '0')>موقوف</option>
    </select>
  </div>

  {{-- حالة النشر --}}
  <div class="col-md-2">
    @php $statusFilter = request('status'); @endphp
    <select name="status" class="form-select" onchange="this.form.submit()">
      <option value="">— حالة النشر —</option>
      <option value="draft"     @selected($statusFilter === 'draft')>مسودة</option>
      <option value="in_review" @selected($statusFilter === 'in_review')>قيد المراجعة</option>
      <option value="published" @selected($statusFilter === 'published')>منشور</option>
      <option value="archived"  @selected($statusFilter === 'archived')>مؤرشف</option>
    </select>
  </div>

  {{-- الجامعة --}}
  <div class="col-md-2">
    <select name="university_id" id="flt_university" class="form-select" onchange="cascadeFilters(); this.form.submit()">
      <option value="">— كل الجامعات —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected(request('university_id') == $u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- الكلية --}}
  <div class="col-md-2">
    <select name="college_id" id="flt_college" class="form-select" onchange="cascadeFilters(); this.form.submit()">
      <option value="">— كل الكليات —</option>
      @foreach($colleges as $c)
        <option value="{{ $c->id }}" data-university="{{ $c->university_id }}"
          @selected(request('college_id') == $c->id)>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- التخصص --}}
  <div class="col-md-2">
    <select name="major_id" id="flt_major" class="form-select" onchange="cascadeFilters(); this.form.submit()">
      <option value="">— كل التخصصات —</option>
      @foreach($majors as $m)
        <option value="{{ $m->id }}" data-college="{{ $m->college_id }}"
          @selected(request('major_id') == $m->id)>{{ $m->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- المادة --}}
  <div class="col-md-3">
    <select name="material_id" id="flt_material" class="form-select" onchange="this.form.submit()">
      <option value="">— كل المواد —</option>
      @foreach(($materials ?? []) as $mat)
        <option value="{{ $mat->id }}" data-major="{{ $mat->major_id }}"
          @selected(request('material_id') == $mat->id)>{{ $mat->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- الدكتور --}}
  <div class="col-md-3">
    <select name="doctor_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الدكاترة —</option>
      @foreach($doctors as $d)
        <option value="{{ $d->id }}" @selected(request('doctor_id') == $d->id)>{{ $d->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- البحث النصي --}}
  <div class="col-md-4">
    <div class="input-group">
      <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="بحث بالعنوان أو الوصف">
      <button class="btn btn-outline-secondary">بحث</button>
      @if(request()->query())
        <a href="{{ route('admin.contents.index') }}" class="btn btn-outline-light text-danger border">مسح</a>
      @endif
    </div>
  </div>
</form>

{{-- جدول المحتوى --}}
<div class="table-responsive">
<table class="table table-hover bg-white align-middle">
  <thead class="table-light">
    <tr>
      <th>العنوان</th>
      <th>النوع</th>
      <th>النطاق الأكاديمي</th>
      <th>المادة / الأجهزة</th>
      <th>المصدر/الملف</th>
      <th>النشر</th>
      <th>التفعيل</th>
      <th class="text-center">إجراءات</th>
    </tr>
  </thead>
  <tbody>
    @forelse($contents as $c)
    <tr>
      {{-- العنوان + الإصدار --}}
      <td class="fw-semibold">
        {{ $c->title }}
        @if(($c->version ?? 1) > 1)
          <span class="badge bg-warning-subtle text-dark border ms-1">v{{ $c->version }}</span>
        @endif
      </td>

      {{-- النوع --}}
      <td>
        @if($c->type === 'file')
          <span class="badge bg-secondary">ملف</span>
        @elseif($c->type === 'video')
          <span class="badge bg-info text-dark">فيديو</span>
        @else
          <span class="badge bg-light text-dark">رابط</span>
        @endif
      </td>

      {{-- النطاق: جامعة / كلية / تخصص --}}
      <td class="small text-muted">
        {{ $c->university->name ?? '—' }}
        @if($c->college) / {{ $c->college->name }} @endif
        @if($c->major)   / {{ $c->major->name }}   @endif
      </td>

      {{-- المادة / الأجهزة --}}
      <td class="small">
        {{ $c->material->name ?? '—' }}
        @php $devCount = $c->devices()->count(); @endphp
        @if($devCount)
          <span class="text-muted">— أجهزة: {{ $devCount }}</span>
        @endif
      </td>

      {{-- المصدر أو الملف --}}
      <td class="small">
        @if($c->type === 'file' && $c->file_url)
          <a href="{{ $c->file_url }}" target="_blank" download>تحميل الملف</a>
        @elseif(in_array($c->type, ['video','link']) && $c->source_url)
          <a href="{{ $c->source_url }}" target="_blank">فتح الرابط</a>
        @else
          —
        @endif
      </td>

      {{-- حالة النشر + التاريخ + الناشر --}}
      <td class="small">
        @switch($c->status)
          @case('draft')     <span class="badge bg-secondary">مسودة</span> @break
          @case('in_review') <span class="badge bg-info text-dark">قيد المراجعة</span> @break
          @case('published') <span class="badge bg-success">منشور</span> @break
          @case('archived')  <span class="badge bg-dark">مؤرشف</span> @break
          @default           <span class="badge bg-light text-dark">{{ $c->status }}</span>
        @endswitch
        @if($c->published_at)
          <div class="text-muted mt-1">
            <i class="bi bi-clock"></i>
            {{ $c->published_at->format('Y-m-d') }}
            @if($c->publishedBy?->name)
              <span class="ms-1">— {{ $c->publishedBy->name }}</span>
            @endif
          </div>
        @endif
      </td>

      {{-- التفعيل --}}
      <td>
        {!! $c->is_active
          ? '<span class="badge bg-success">مفعل</span>'
          : '<span class="badge bg-secondary">موقوف</span>' !!}
      </td>

      {{-- الإجراءات --}}
      <td class="text-center">
        <a href="{{ route('admin.contents.edit', $c) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
        <form action="{{ route('admin.contents.destroy', $c) }}" method="POST" class="d-inline">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف المحتوى؟')">حذف</button>
        </form>
      </td>
    </tr>
    @empty
    <tr><td colspan="8" class="text-center text-muted">لا توجد بيانات.</td></tr>
    @endforelse
  </tbody>
</table>
</div>

{{-- روابط الترقيم --}}
{{ $contents->links('vendor.pagination.bootstrap-custom') }}

@endsection

@push('scripts')
<script>
function cascadeFilters(){
  const uni = document.getElementById('flt_university')?.value || '';
  const col = document.getElementById('flt_college');
  const maj = document.getElementById('flt_major');
  const mat = document.getElementById('flt_material');

  // الكليات حسب الجامعة
  if(col){
    [...col.options].forEach(o => {
      if (!o.value) return;
      const show = !uni || (o.dataset.university === uni);
      o.hidden = !show; if (!show && o.selected) o.selected = false;
    });
  }

  // التخصصات حسب الكلية
  const colVal = col?.value || '';
  if(maj){
    [...maj.options].forEach(o => {
      if (!o.value) return;
      const show = !colVal || (o.dataset.college === colVal);
      o.hidden = !show; if (!show && o.selected) o.selected = false;
    });
  }

  // المواد حسب التخصص
  const majVal = maj?.value || '';
  if(mat){
    [...mat.options].forEach(o => {
      if (!o.value) return;
      const show = !majVal || (o.dataset.major === majVal);
      o.hidden = !show; if (!show && o.selected) o.selected = false;
    });
  }
}

document.addEventListener('DOMContentLoaded', cascadeFilters);
</script>
@endpush
