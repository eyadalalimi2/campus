<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">الاسم *</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $device->name ?? '') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">الصورة</label>
    <input type="file" name="image" class="form-control">
    @if(!empty($device?->image_path))
      <img src="{{ asset('storage/'.$device->image_path) }}" class="img-thumbnail mt-2" style="height:64px">
    @endif
  </div>

  <div class="col-md-3">
    <label class="form-label">الترتيب</label>
    <input type="number" name="order_index" class="form-control" value="{{ old('order_index', $device->order_index ?? 0) }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">الحالة *</label>
    <select name="status" class="form-select">
      @foreach(['published'=>'مفعل','draft'=>'موقوف'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('status',$device->status ?? 'published')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>
  

  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-1">
      <label class="form-label mb-0">المواد المرتبطة</label>
      <div>
        <a href="#" id="med-subjects-toggle" class="small">تحديد الكل</a>
      </div>
    </div>

    <div class="card">
      <div class="card-body p-2" style="max-height:320px; overflow:auto;">
        <div class="row g-2" id="med-subjects-list">
          @foreach(\App\Models\MedSubject::orderBy('name')->get() as $s)
            <div class="col-12 col-md-6 col-lg-4">
              <div class="form-check">
                <input class="form-check-input med-subject-checkbox" type="checkbox"
                       name="subject_ids[]" value="{{ $s->id }}" id="med_subject_{{ $s->id }}"
                       @checked(in_array($s->id, $selected ?? []))>
                <label class="form-check-label" for="med_subject_{{ $s->id }}">{{ $s->name }}</label>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>

  @push('scripts')
  <script>
    (function(){
      const toggle = document.getElementById('med-subjects-toggle');
      const container = document.getElementById('med-subjects-list');
      if(!toggle || !container) return;

      function setAll(checked){
        container.querySelectorAll('input.med-subject-checkbox').forEach(cb => cb.checked = checked);
      }

      function allChecked(){
        const boxes = Array.from(container.querySelectorAll('input.med-subject-checkbox'));
        return boxes.length && boxes.every(b => b.checked);
      }

      // initialize link text based on current state
      function refreshLink(){
        toggle.textContent = allChecked() ? 'إلغاء التحديد' : 'تحديد الكل';
      }

      toggle.addEventListener('click', function(e){
        e.preventDefault();
        const shouldCheck = !allChecked();
        setAll(shouldCheck);
        refreshLink();
      });

      // update link if user manually toggles individual checkboxes
      container.addEventListener('change', function(e){
        if(e.target && e.target.classList && e.target.classList.contains('med-subject-checkbox')){
          refreshLink();
        }
      });

      // run initial refresh after DOM ready
      document.addEventListener('DOMContentLoaded', refreshLink);
      // also call immediately in case DOMContentLoaded already fired
      refreshLink();
    })();
  </script>
  @endpush
</div>
