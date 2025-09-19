@php
  $isEdit    = isset($asset);
  $cat       = old('category', $asset->category ?? 'file');
  $statusVal = old('status', $asset->status ?? 'draft');

  // تحضير قائمة "التخصصات العامة" (Public Majors)
  $publicMajors = $publicMajors
      ?? \App\Models\PublicMajor::with('publicCollege')->active()->orderBy('name')->get();

  // المختارات الحالية للتخصصات العامة
  $selectedPublicMajors = collect(old(
    'public_major_ids',
    $isEdit
      ? ($asset->relationLoaded('publicMajors')
          ? $asset->publicMajors->pluck('id')->all()
          : \App\Models\PublicMajor::select('public_majors.id')
              ->join('asset_public_major as apm','apm.public_major_id','=','public_majors.id')
              ->where('apm.asset_id', $asset->id)
              ->pluck('public_majors.id')->all()
        )
      : []
  ))->map(fn($v)=> (int)$v)->all();

  // التخصص الرئيسي (Primary)
  $currentPrimaryId = (int) old(
    'primary_public_major_id',
    $isEdit
      ? (optional(
            $asset->relationLoaded('publicMajors')
              ? $asset->publicMajors->firstWhere('pivot.is_primary', 1)
              : \App\Models\PublicMajor::select('public_majors.*','apm.is_primary')
                  ->join('asset_public_major as apm','apm.public_major_id','=','public_majors.id')
                  ->where('apm.asset_id', $asset->id)
                  ->where('apm.is_primary', 1)
                  ->first()
        )?->id ?? 0)
      : 0
  );

  // أولوية العرض (priority) لكل تخصص عام
  $priorities = old('public_major_priorities', (function() use ($isEdit, $asset){
      if (! $isEdit) return [];
      if ($asset->relationLoaded('publicMajors')) {
          return $asset->publicMajors->mapWithKeys(fn($m)=> [$m->id => (int)($m->pivot->priority ?? 0)])->all();
      }
      return \App\Models\PublicMajor::select('public_majors.id','apm.priority')
          ->join('asset_public_major as apm','apm.public_major_id','=','public_majors.id')
          ->where('apm.asset_id', $asset->id)
          ->get()
          ->mapWithKeys(fn($r)=> [$r->id => (int)($r->priority ?? 0)])->all();
  })());
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

  {{-- الجمهور العام (تخصصات عامة) --}}
  <div class="col-12">
    <label class="form-label">التخصصات العامة (يمكن اختيار أكثر من واحد)</label>
    <select name="public_major_ids[]" id="public_major_ids" class="form-select" multiple size="8">
      @foreach($publicMajors->groupBy(fn($m) => $m->publicCollege?->name ?? 'بدون كلية') as $collegeName => $majors)
        <optgroup label="{{ $collegeName }}">
          @foreach($majors as $m)
            <option value="{{ $m->id }}" @selected(in_array($m->id, $selectedPublicMajors))>
              {{ $m->name }}
            </option>
          @endforeach
        </optgroup>
      @endforeach
    </select>
    <div class="form-text">
      اتركه فارغًا ليكون الأصل عامًا للجميع. عند اختيار تخصصات عامة، سيظهر فقط لمن ينتمي إليها.
    </div>
    @error('public_major_ids')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    @error('public_major_ids.*')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  {{-- التخصص الرئيسي (Primary) --}}
  <div class="col-md-6">
    <label class="form-label">التخصص الرئيسي (Primary)</label>
    <select name="primary_public_major_id" id="primary_public_major_id" class="form-select">
      <option value="">— لا شيء —</option>
      @php $selectedIds = collect($selectedPublicMajors); @endphp
      @foreach($publicMajors as $m)
        @if($selectedIds->contains($m->id))
          <option value="{{ $m->id }}" @selected($currentPrimaryId === (int)$m->id)>{{ $m->name }}</option>
        @endif
      @endforeach
    </select>
    <div class="form-text">اختياري؛ يحدد التصنيف الرئيسي لعرض البطاقة.</div>
    @error('primary_public_major_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  {{-- الأولوية (Priority) --}}
  <div class="col-12">
    <label class="form-label">الأولوية (Priority) لكل تخصص عام</label>
    <div class="row g-2">
      @foreach($publicMajors as $m)
        @php $val = (int)($priorities[$m->id] ?? 0); @endphp
        <div class="col-md-6 d-flex align-items-center gap-2">
          <span class="text-muted flex-grow-1">
            {{ $m->publicCollege?->name }} — {{ $m->name }}
          </span>
          <input type="number" name="public_major_priorities[{{ $m->id }}]" class="form-control" style="max-width:120px" value="{{ $val }}">
        </div>
      @endforeach
    </div>
    <div class="form-text">استخدم الأرقام لتحديد ترتيب العرض داخل الأصل.</div>
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
  const $pmSel = document.getElementById('public_major_ids');
  const $primarySel = document.getElementById('primary_public_major_id');

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

  // تحديث خيارات "Primary" لتعرض فقط المختارة في قائمة التخصصات العامة
  function syncPrimaryOptions(){
    if(!$pmSel || !$primarySel) return;
    const selected = new Set([...$pmSel.options].filter(o=>o.selected).map(o=>o.value));
    [...$primarySel.options].forEach(o=>{
      if (!o.value) return; // تخطّي "لا شيء"
      const show = selected.has(o.value);
      o.hidden = !show;
      if(!show && o.selected) o.selected = false;
    });
  }

  // init
  toggleCategory();
  filterDevicesByMaterial();
  filterProgramsByDiscipline();
  syncPrimaryOptions();

  // events
  $cat?.addEventListener('change', toggleCategory);
  $mat?.addEventListener('change', filterDevicesByMaterial);
  $disc?.addEventListener('change', filterProgramsByDiscipline);
  $pmSel?.addEventListener('change', syncPrimaryOptions);
})();
</script>
@endpush
