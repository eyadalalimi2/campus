@php
  $type   = old('type', $content->type ?? 'file');
  $selUni = old('university_id', $content->university_id ?? '');
  $selCol = old('college_id',    $content->college_id    ?? '');
  $selMaj = old('major_id',      $content->major_id      ?? '');
  $selMat = old('material_id',   $content->material_id   ?? '');
  $selectedDevices = old('device_ids', $selectedDevices ?? []);
  $statusVal = old('status', $content->status ?? 'draft');
@endphp

<div class="row g-3">
  {{-- العنوان --}}
  <div class="col-md-6">
    <label class="form-label">العنوان <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control" required value="{{ old('title', $content->title ?? '') }}">
  </div>

  {{-- النوع --}}
  <div class="col-md-3">
    <label class="form-label">النوع <span class="text-danger">*</span></label>
    <select name="type" id="type" class="form-select" required onchange="cnt_switchType()">
      <option value="file"  @selected($type==='file')>ملف</option>
      <option value="video" @selected($type==='video')>فيديو (رابط)</option>
      <option value="link"  @selected($type==='link')>رابط خارجي</option>
    </select>
  </div>

  {{-- التفعيل --}}
  <div class="col-md-3">
    <label class="form-label">التفعيل</label>
    <div class="form-check pt-2">
      <input type="hidden" name="is_active" value="0">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="c_active"
        {{ old('is_active', $content->is_active ?? true) ? 'checked' : '' }}>
      <label class="form-check-label" for="c_active">مفعل</label>
    </div>
  </div>

  {{-- حالة النشر --}}
  <div class="col-md-4">
    <label class="form-label">حالة النشر <span class="text-danger">*</span></label>
    <select name="status" id="status" class="form-select" required>
      <option value="draft"     @selected($statusVal === 'draft')>مسودة</option>
      <option value="in_review" @selected($statusVal === 'in_review')>قيد المراجعة</option>
      <option value="published" @selected($statusVal === 'published')>منشور</option>
      <option value="archived"  @selected($statusVal === 'archived')>مؤرشف</option>
    </select>
  </div>

  {{-- معلومات النشر (عرض فقط إن كانت موجودة) --}}
  @if(!empty($content?->published_at))
    <div class="col-md-4">
      <label class="form-label">تاريخ النشر</label>
      <input type="text" class="form-control" value="{{ $content->published_at->format('Y-m-d H:i') }}" readonly>
    </div>
    <div class="col-md-4">
      <label class="form-label">نُشر بواسطة</label>
      <input type="text" class="form-control" value="{{ $content->publishedBy?->name ?? '—' }}" readonly>
    </div>
  @endif

  {{-- الملف --}}
  <div class="col-md-12 cnt-type-file">
    <label class="form-label">الملف <span class="text-danger" id="req_file" style="display:none;">(مطلوب)</span></label>
    <input
      type="file"
      name="file"
      id="file_input"
      class="form-control"
      accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.7z,.rar,.tar,.gz">
    @if(!empty($content?->file_url))
      <a href="{{ $content->file_url }}" target="_blank" class="small d-block mt-2">عرض الملف الحالي</a>
    @endif
    <div class="form-text">الحد الأقصى 100MB. الصيغ: PDF/DOC(X)/XLS(X)/PPT(X)/TXT/ZIP/7Z/RAR/TAR/GZ</div>
  </div>

  {{-- الرابط (لفيديو/رابط) --}}
  <div class="col-md-12 cnt-type-link">
    <label class="form-label">الرابط <span class="text-danger" id="req_link" style="display:none;">(مطلوب)</span></label>
    <input
      type="url"
      name="source_url"
      id="source_url"
      class="form-control"
      placeholder="https://www.youtube.com/watch?v=... أو https://example.com/page"
      value="{{ old('source_url', $content->source_url ?? '') }}">
    <div class="form-text">لفيديو يوتيوب: يفضل رابط من youtube.com أو youtu.be</div>
  </div>

  {{-- الوصف --}}
  <div class="col-12">
    <label class="form-label">الوصف</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $content->description ?? '') }}</textarea>
  </div>

  {{-- الإصدار والتغيير --}}
  <div class="col-md-2">
    <label class="form-label">رقم الإصدار</label>
    <input type="number" name="version" class="form-control" min="1"
           value="{{ old('version', $content->version ?? 1) }}">
  </div>
  <div class="col-md-10">
    <label class="form-label">سجلّ التغييرات (Changelog)</label>
    <input type="text" name="changelog" class="form-control"
           placeholder="اذكر ما تغيّر في هذا الإصدار"
           value="{{ old('changelog', $content->changelog ?? '') }}">
  </div>

  {{-- الجامعة --}}
  <div class="col-md-3">
    <label class="form-label">الجامعة <span class="text-danger">*</span></label>
    <select name="university_id" id="university_select" class="form-select" required>
      <option value="">— اختر —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected($selUni == $u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- الكلية --}}
  <div class="col-md-3">
    <label class="form-label">الكلية</label>
    <select name="college_id" id="college_select" class="form-select">
      <option value="">— اختر —</option>
      @foreach($colleges as $c)
        <option value="{{ $c->id }}" data-university="{{ $c->university_id }}"
          @selected($selCol == $c->id)>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- التخصص --}}
  <div class="col-md-3">
    <label class="form-label">التخصص</label>
    <select name="major_id" id="major_select" class="form-select">
      <option value="">— اختر —</option>
      @foreach($majors as $m)
        <option value="{{ $m->id }}" data-college="{{ $m->college_id }}"
          @selected($selMaj == $m->id)>{{ $m->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- المادة --}}
  <div class="col-md-3">
    <label class="form-label">المادة</label>
    <select name="material_id" id="material_select" class="form-select">
      <option value="">— اختر —</option>
      @foreach($materials as $mat)
        <option value="{{ $mat->id }}" data-major="{{ $mat->major_id }}"
          @selected($selMat == $mat->id)>{{ $mat->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- الدكتور --}}
  <div class="col-md-4">
    <label class="form-label">الدكتور</label>
    <select name="doctor_id" class="form-select">
      <option value="">— بدون —</option>
      @foreach($doctors as $d)
        <option value="{{ $d->id }}" @selected(old('doctor_id', $content->doctor_id ?? '') == $d->id)>{{ $d->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- الأجهزة المرتبطة بالمادة --}}
  <div class="col-md-8">
    <label class="form-label">الأجهزة المرتبطة بالمادة</label>
    <select name="device_ids[]" id="device_select" class="form-select" multiple size="6">
      @foreach($devices as $d)
        <option value="{{ $d->id }}" data-material="{{ $d->material_id }}"
          @selected(in_array($d->id, $selectedDevices))>{{ $d->name }}</option>
      @endforeach
    </select>
    <div class="form-text">اختر أجهزة تابعة للمادة المختارة.</div>
  </div>

  {{-- تنبيه سلامة العلاقات (توضيحي فقط) --}}
  <div class="col-12">
    <div class="alert alert-info small mb-0">
      ملاحظة: سيتم رفض الحفظ إذا لم تتبع (الكلية/التخصص) نفس الجامعة، وفق قيود السلامة في قاعدة البيانات.
    </div>
  </div>
</div>

@push('scripts')
<script>
function cnt_switchType(){
  const t = document.getElementById('type').value;
  const fileWrap = document.querySelector('.cnt-type-file');
  const linkWrap = document.querySelector('.cnt-type-link');
  const fileInput = document.getElementById('file_input');
  const srcInput  = document.getElementById('source_url');
  const reqFile   = document.getElementById('req_file');
  const reqLink   = document.getElementById('req_link');

  // إظهار/إخفاء الأقسام
  fileWrap.style.display = (t === 'file') ? 'block' : 'none';
  linkWrap.style.display = (t === 'video' || t === 'link') ? 'block' : 'none';

  // ضبط required ديناميكيًا:
  // - عند الإنشاء مع نوع "ملف": الملف مطلوب
  // - عند التعديل: الملف ليس مطلوبًا لو كان هناك ملف سابق
  const isEditing = !!@json(!empty($content?->id));
  const hasOldFile = !!@json(!empty($content?->file_path));

  if (t === 'file') {
    if (!isEditing || !hasOldFile) {
      fileInput?.setAttribute('required', 'required');
      reqFile?.style?.setProperty('display','inline');
    } else {
      fileInput?.removeAttribute('required');
      reqFile?.style?.setProperty('display','none');
    }
    srcInput?.removeAttribute('required');
    reqLink?.style?.setProperty('display','none');
  } else {
    fileInput?.removeAttribute('required');
    reqFile?.style?.setProperty('display','none');
    srcInput?.setAttribute('required', 'required');
    reqLink?.style?.setProperty('display','inline');
  }
}

function cnt_cascade(){
  const uni = document.getElementById('university_select')?.value || '';
  const col = document.getElementById('college_select');
  const maj = document.getElementById('major_select');
  const mat = document.getElementById('material_select');
  const dev = document.getElementById('device_select');

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

  // الأجهزة حسب المادة
  const matVal = mat?.value || '';
  if(dev){
    [...dev.options].forEach(o => {
      if (!o.value) return;
      const show = !matVal || (o.dataset.material === matVal);
      o.hidden = !show; if (!show && o.selected) o.selected = false;
    });
  }
}

document.addEventListener('DOMContentLoaded', function(){
  cnt_switchType();
  cnt_cascade();
  ['type','university_select','college_select','major_select','material_select'].forEach(id => {
    const el = document.getElementById(id);
    if(!el) return;
    el.addEventListener('change', function(){
      if(id === 'type') cnt_switchType();
      cnt_cascade();
    });
  });
});
</script>
@endpush
