@php
  $isEdit = isset($asset);
  $cat    = old('category', $asset->category ?? 'file');
  $statusVal = old('status', $asset->status ?? 'draft');

  // جمهور الأصل (تخصّصات)
  $selectedMajors = old(
    'audience_major_ids',
    $isEdit
      ? ($asset->relationLoaded('audienceMajors')
          ? $asset->audienceMajors->pluck('id')->all()
          : \App\Models\Major::select('majors.id')
              ->join('asset_audiences as aa','aa.major_id','=','majors.id')
              ->where('aa.asset_id',$asset->id)
              ->pluck('majors.id')->all()
        )
      : []
  );
@endphp

<div class="row g-3">

  {{-- الفئة --}}
  <div class="col-md-3">
    <label class="form-label">الفئة <span class="text-danger">*</span></label>
    <select name="category" id="category" class="form-select" required>
      <option value="youtube"       @selected($cat==='youtube')>يوتيوب</option>
      <option value="file"          @selected($cat==='file')>ملف</option>
      <option value="reference"     @selected($cat==='reference')>مرجع</option>
      <option value="question_bank" @selected($cat==='question_bank')>بنك أسئلة</option>
      <option value="curriculum"    @selected($cat==='curriculum')>منهج</option>
      <option value="book"          @selected($cat==='book')>كتاب</option>
    </select>
  </div>

  {{-- العنوان --}}
  <div class="col-md-6">
    <label class="form-label">العنوان <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control" required value="{{ old('title',$asset->title ?? '') }}">
  </div>

  {{-- التفعيل --}}
  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check pt-2">
      <input type="hidden" name="is_active" value="0">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
             {{ old('is_active', $asset->is_active ?? true) ? 'checked':'' }}>
      <label class="form-check-label" for="is_active">مفعل</label>
    </div>
  </div>

  {{-- الوصف --}}
  <div class="col-12">
    <label class="form-label">الوصف (اختياري)</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description',$asset->description ?? '') }}</textarea>
  </div>

  {{-- المجال --}}
  <div class="col-md-4">
    <label class="form-label">المجال (اختياري)</label>
    <select name="discipline_id" id="discipline_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\Discipline::orderBy('name')->get() as $disc)
        <option value="{{ $disc->id }}" @selected(old('discipline_id',$asset->discipline_id ?? '') == $disc->id)>{{ $disc->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- البرنامج (يُرشَّح بالمجال) --}}
  <div class="col-md-4">
    <label class="form-label">البرنامج (اختياري)</label>
    <select name="program_id" id="program_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\Program::with('discipline')->orderBy('name')->get() as $prog)
        <option value="{{ $prog->id }}"
                data-discipline="{{ $prog->discipline_id }}"
                @selected(old('program_id',$asset->program_id ?? '') == $prog->id)>
          {{ $prog->name }} ({{ $prog->discipline?->name }})
        </option>
      @endforeach
    </select>
    <div class="form-text">عند اختيار برنامج يجب أن ينتمي لنفس المجال (مفروض أيضًا بقيد قاعدة البيانات).</div>
  </div>

  {{-- حالة النشر --}}
  <div class="col-md-4">
    <label class="form-label">حالة النشر <span class="text-danger">*</span></label>
    <select name="status" id="status" class="form-select" required>
      <option value="draft"      @selected($statusVal==='draft')>مسودة</option>
      <option value="in_review"  @selected($statusVal==='in_review')>قيد المراجعة</option>
      <option value="published"  @selected($statusVal==='published')>منشور</option>
      <option value="archived"   @selected($statusVal==='archived')>مؤرشف</option>
    </select>
    <div class="form-text">عند اختيار «منشور» سيتم ضبط الناشر وتاريخ النشر تلقائيًا.</div>
  </div>

  {{-- المادة --}}
  <div class="col-md-4">
    <label class="form-label">المادة <span class="text-danger">*</span></label>
    <select name="material_id" id="material_id" class="form-select" required>
      <option value="">— اختر —</option>
      @foreach(\App\Models\Material::orderBy('name')->get() as $m)
        <option value="{{ $m->id }}" @selected(old('material_id',$asset->material_id ?? '')==$m->id)>{{ $m->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- الجهاز (مفلتر بالمادة) --}}
  <div class="col-md-4">
    <label class="form-label">الجهاز/المهمة (اختياري)</label>
    <select name="device_id" id="device_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\Device::with('material')->orderBy('name')->get() as $d)
        <option value="{{ $d->id }}" data-material="{{ $d->material_id }}"
                @selected(old('device_id',$asset->device_id ?? '')==$d->id)>
          {{ $d->name }} ({{ $d->material->name }})
        </option>
      @endforeach
    </select>
  </div>

  {{-- الدكتور --}}
  <div class="col-md-4">
    <label class="form-label">الدكتور (اختياري)</label>
    <select name="doctor_id" class="form-select">
      <option value="">— بدون —</option>
      @foreach(\App\Models\Doctor::orderBy('name')->get() as $doc)
        <option value="{{ $doc->id }}" @selected(old('doctor_id',$asset->doctor_id ?? '')==$doc->id)>{{ $doc->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- جمهور الأصل (تخصّصات) --}}
  <div class="col-12">
    <label class="form-label">الجمهور المستهدف (التخصّصات)</label>
    <select name="audience_major_ids[]" id="audience_major_ids" class="form-select" multiple size="8">
      @foreach(\App\Models\Major::with('college')->orderBy('name')->get() as $maj)
        <option value="{{ $maj->id }}" @selected(in_array($maj->id, $selectedMajors))>
          {{ $maj->college?->name }} — {{ $maj->name }}
        </option>
      @endforeach
    </select>
    <div class="form-text">
      اتركه فارغًا ليكون الأصل عامًا لكل التخصّصات. إن اخترت واحدًا أو أكثر، سيظهر فقط لطلاب تلك التخصّصات.
    </div>
  </div>

  {{-- YouTube --}}
  <div class="col-md-6 cat-youtube">
    <label class="form-label">رابط الفيديو (YouTube) <span class="text-danger youtube-required d-none">*</span></label>
    <input type="url" name="video_url" id="video_url" class="form-control"
           placeholder="https://www.youtube.com/watch?v=XXXXXXXXXXX"
           value="{{ old('video_url',$asset->video_url ?? '') }}">
    <div class="form-text">عند اختيار فئة «يوتيوب» يكون هذا الحقل مطلوبًا.</div>
  </div>

  {{-- ملف --}}
  <div class="col-md-6 cat-file">
    <label class="form-label">ملف <span class="text-danger file-required d-none">*</span></label>
    <input type="file" name="file" id="file_input" class="form-control"
           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.7z,.rar,.tar,.gz">
    @if(!empty($asset?->file_url))
      <div class="form-text"><a href="{{ $asset->file_url }}" target="_blank" download>تنزيل الملف الحالي</a></div>
    @endif
    <div class="form-text">الأنواع المسموح بها حتى 100MB.</div>
  </div>

  {{-- مرجع/بنك أسئلة/منهج/كتاب --}}
  <div class="col-md-6 cat-link">
    <label class="form-label">رابط خارجي (اختياري)</label>
    <input type="url" name="external_url" id="external_url" class="form-control"
           placeholder="https://example.com/page"
           value="{{ old('external_url',$asset->external_url ?? '') }}">
    <div class="form-text">يمكن تركه فارغًا ورفع ملف بدلًا عنه (ستتحقق طبقة التحقق في السيرفر).</div>
  </div>

</div>

@push('scripts')
<script>
(function(){
  const $cat   = document.getElementById('category');
  const $disc  = document.getElementById('discipline_id');
  const $prog  = document.getElementById('program_id');
  const $mat   = document.getElementById('material_id');
  const $dev   = document.getElementById('device_id');
  const $file  = document.getElementById('file_input');
  const $yt    = document.getElementById('video_url');

  function toggleCategory(){
    const c = $cat.value;
    document.querySelectorAll('.cat-youtube').forEach(el=>el.style.display = (c==='youtube') ? '' : 'none');
    document.querySelectorAll('.cat-file').forEach(el=>el.style.display    = (c==='file')    ? '' : 'none');
    document.querySelectorAll('.cat-link').forEach(el=>el.style.display    = (['reference','question_bank','curriculum','book'].includes(c) ? '' : 'none'));

    // required ديناميكي (التحقق النهائي في السيرفر)
    const needFile = (c === 'file');
    const needYt   = (c === 'youtube');

    if ($file) {
      $file.required = needFile;
      document.querySelector('.file-required')?.classList.toggle('d-none', !needFile);
    }
    if ($yt) {
      $yt.required = needYt;
      document.querySelector('.youtube-required')?.classList.toggle('d-none', !needYt);
    }
  }

  function filterDevicesByMaterial(){
    const mat = $mat?.value || '';
    if(!$dev) return;
    [...$dev.options].forEach(o=>{
      if (!o.value) return;
      const show = !mat || (o.dataset.material === mat);
      o.hidden = !show;
      if(!show && o.selected) o.selected = false;
    });
  }

  function filterProgramsByDiscipline(){
    const disc = $disc?.value || '';
    if(!$prog) return;
    [...$prog.options].forEach(o=>{
      if (!o.value) return;
      const show = !disc || (o.dataset.discipline === disc);
      o.hidden = !show;
      if(!show && o.selected) o.selected = false;
    });
  }

  // init
  toggleCategory();
  filterDevicesByMaterial();
  filterProgramsByDiscipline();

  // events
  $cat?.addEventListener('change', toggleCategory);
  $mat?.addEventListener('change', filterDevicesByMaterial);
  $disc?.addEventListener('change', filterProgramsByDiscipline);
})();
</script>
@endpush
