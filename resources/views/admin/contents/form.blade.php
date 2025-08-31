@php $isEdit = isset($content) && $content; @endphp

<div class="row g-3">

  <div class="col-md-6">
    <label class="form-label">العنوان</label>
    <input type="text" name="title" class="form-control" required value="{{ old('title', $content->title ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">نوع المحتوى</label>
    <select name="type" id="type_select" class="form-select" onchange="toggleType()" required>
      @php $t = old('type', $content->type ?? 'file'); @endphp
      <option value="file"  @selected($t==='file')>ملف</option>
      <option value="video" @selected($t==='video')>فيديو (YouTube)</option>
      <option value="link"  @selected($t==='link')>رابط خارجي</option>
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">النطاق</label>
    <select name="scope" id="scope_select" class="form-select" onchange="toggleScope()" required>
      @php $sc = old('scope', $content->scope ?? 'university'); @endphp
      <option value="university" @selected($sc==='university')>خاص بجامعة</option>
      <option value="global"     @selected($sc==='global')>عام (كل الجامعات)</option>
    </select>
  </div>

  <div class="col-12">
    <label class="form-label">الوصف (اختياري)</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $content->description ?? '') }}</textarea>
  </div>

  <!-- ملف -->
  <div class="col-md-6 type-file">
    <label class="form-label">الملف</label>
    <input type="file" name="file" class="form-control" @if(!$isEdit || ($isEdit && ($content->type==='file' && !$content->file_path))) required @endif>
    @if(!empty($content?->file_url))
      <div class="form-text"><a href="{{ $content->file_url }}" target="_blank" download>تنزيل الملف الحالي</a></div>
    @endif
  </div>

  <!-- رابط/فيديو -->
  <div class="col-md-6 type-link">
    <label class="form-label">الرابط (YouTube/خارجي)</label>
    <input type="url" name="source_url" class="form-control" value="{{ old('source_url', $content->source_url ?? '') }}">
  </div>

  <!-- نطاق الجامعة -->
  <div class="col-12 scope-university"><hr><strong>تحديد الجامعة/الكلية/التخصص (للمحتوى الخاص)</strong></div>

  <div class="col-md-4 scope-university">
    <label class="form-label">الجامعة</label>
    <select name="university_id" id="university_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\University::orderBy('name')->get() as $u)
        <option value="{{ $u->id }}" @selected(old('university_id', $content->university_id ?? '')==$u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4 scope-university">
    <label class="form-label">الكلية (اختياري)</label>
    <select name="college_id" id="college_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\College::orderBy('name')->get() as $c)
        <option value="{{ $c->id }}" @selected(old('college_id', $content->college_id ?? '')==$c->id) data-university="{{ $c->university_id }}">
          {{ $c->name }} ({{ $c->university->name }})
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4 scope-university">
    <label class="form-label">التخصص (اختياري)</label>
    <select name="major_id" id="major_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\Major::with('college')->orderBy('name')->get() as $m)
        <option value="{{ $m->id }}" @selected(old('major_id', $content->major_id ?? '')==$m->id) data-college="{{ $m->college_id }}">
          {{ $m->name }} ({{ $m->college->name }})
        </option>
      @endforeach
    </select>
  </div>

  <!-- ربط دكتور (اختياري) -->
  <div class="col-12"><hr><strong>الدكتور المرتبط (اختياري)</strong></div>
  <div class="col-md-6">
    <label class="form-label">الدكتور</label>
    <select name="doctor_id" class="form-select">
      <option value="">— بدون —</option>
      @foreach(\App\Models\Doctor::orderBy('name')->get() as $d)
        <option value="{{ $d->id }}" @selected(old('doctor_id', $content->doctor_id ?? '')==$d->id)>{{ $d->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
        {{ old('is_active', $content->is_active ?? true) ? 'checked':'' }}>
      <label class="form-check-label" for="is_active">مفعل</label>
    </div>
  </div>
</div>

@push('scripts')
<script>
function toggleType(){
  const t = document.getElementById('type_select').value;
  document.querySelectorAll('.type-file').forEach(el => el.style.display = (t==='file'?'':'none'));
  document.querySelectorAll('.type-link').forEach(el => el.style.display = (t!=='file'?'':'none'));
}

function toggleScope(){
  const sc = document.getElementById('scope_select').value;
  document.querySelectorAll('.scope-university').forEach(el => el.style.display = (sc==='university'?'':'none'));
}

function filterCollegesByUniversity(){
  const uniId = document.getElementById('university_id').value;
  document.querySelectorAll('#college_id option[data-university]').forEach(o=>{
    o.hidden = (uniId && o.dataset.university !== uniId);
  });
}
function filterMajorsByCollege(){
  const colId = document.getElementById('college_id').value;
  document.querySelectorAll('#major_id option[data-college]').forEach(o=>{
    o.hidden = (colId && o.dataset.college !== colId);
  });
}

document.getElementById('type_select').addEventListener('change', toggleType);
document.getElementById('scope_select').addEventListener('change', toggleScope);
document.getElementById('university_id').addEventListener('change', filterCollegesByUniversity);
document.getElementById('college_id').addEventListener('change', filterMajorsByCollege);

// تفعيل عند التحميل
toggleType(); toggleScope(); filterCollegesByUniversity(); filterMajorsByCollege();
</script>
@endpush
