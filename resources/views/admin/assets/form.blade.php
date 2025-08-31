@php $isEdit = isset($asset); @endphp
<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">الفئة</label>
    @php $cat = old('category',$asset->category ?? 'file'); @endphp
    <select name="category" id="category" class="form-select" onchange="toggleCategory()" required>
      <option value="youtube"       @selected($cat==='youtube')>يوتيوب</option>
      <option value="file"          @selected($cat==='file')>ملف</option>
      <option value="reference"     @selected($cat==='reference')>مرجع</option>
      <option value="question_bank" @selected($cat==='question_bank')>بنك أسئلة</option>
      <option value="curriculum"    @selected($cat==='curriculum')>منهج</option>
      <option value="book"          @selected($cat==='book')>كتاب</option>
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">العنوان</label>
    <input type="text" name="title" class="form-control" required value="{{ old('title',$asset->title ?? '') }}">
  </div>
  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active',$asset->is_active ?? true) ? 'checked':'' }}>
      <label class="form-check-label" for="is_active">مفعل</label>
    </div>
  </div>

  <div class="col-12">
    <label class="form-label">الوصف (اختياري)</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description',$asset->description ?? '') }}</textarea>
  </div>

  <div class="col-md-4">
    <label class="form-label">المادة</label>
    <select name="material_id" id="material_id" class="form-select" required>
      <option value="">— اختر —</option>
      @foreach(\App\Models\Material::orderBy('name')->get() as $m)
        <option value="{{ $m->id }}" @selected(old('material_id',$asset->material_id ?? '')==$m->id)>{{ $m->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">الجهاز/المهمة (اختياري)</label>
    <select name="device_id" id="device_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\Device::with('material')->orderBy('name')->get() as $d)
        <option value="{{ $d->id }}" @selected(old('device_id',$asset->device_id ?? '')==$d->id) data-material="{{ $d->material_id }}">
          {{ $d->name }} ({{ $d->material->name }})
        </option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">الدكتور (اختياري)</label>
    <select name="doctor_id" class="form-select">
      <option value="">— بدون —</option>
      @foreach(\App\Models\Doctor::orderBy('name')->get() as $doc)
        <option value="{{ $doc->id }}" @selected(old('doctor_id',$asset->doctor_id ?? '')==$doc->id)>{{ $doc->name }}</option>
      @endforeach
    </select>
  </div>

  <!-- YouTube -->
  <div class="col-md-6 cat-youtube">
    <label class="form-label">رابط الفيديو (YouTube)</label>
    <input type="url" name="video_url" class="form-control" value="{{ old('video_url',$asset->video_url ?? '') }}">
  </div>

  <!-- ملف -->
  <div class="col-md-6 cat-file">
    <label class="form-label">ملف</label>
    <input type="file" name="file" class="form-control" @if(!$isEdit || ($isEdit && $asset->category==='file' && !$asset->file_path)) required @endif>
    @if(!empty($asset?->file_url))
      <div class="form-text"><a href="{{ $asset->file_url }}" target="_blank" download>تنزيل الملف الحالي</a></div>
    @endif
  </div>

  <!-- مرجع/بنك أسئلة/منهج/كتاب -->
  <div class="col-md-6 cat-link">
    <label class="form-label">رابط خارجي (اختياري)</label>
    <input type="url" name="external_url" class="form-control" value="{{ old('external_url',$asset->external_url ?? '') }}">
    <div class="form-text">يمكن تركه فارغًا ورفع ملف بدلًا عنه.</div>
  </div>
</div>

@push('scripts')
<script>
function toggleCategory(){
  const c = document.getElementById('category').value;
  document.querySelectorAll('.cat-youtube').forEach(el=>el.style.display = (c==='youtube' ? '' : 'none'));
  document.querySelectorAll('.cat-file').forEach(el=>el.style.display    = (c==='file'    ? '' : 'none'));
  document.querySelectorAll('.cat-link').forEach(el=>el.style.display    = (['reference','question_bank','curriculum','book'].includes(c) ? '' : 'none'));
}
function filterDevicesByMaterial(){
  const mat = document.getElementById('material_id').value;
  document.querySelectorAll('#device_id option[data-material]').forEach(o=>o.hidden = (mat && o.dataset.material !== mat));
}
document.getElementById('category').addEventListener('change', toggleCategory);
document.getElementById('material_id').addEventListener('change', filterDevicesByMaterial);
toggleCategory(); filterDevicesByMaterial();
</script>
@endpush
