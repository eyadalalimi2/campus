<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">الاسم *</label>
    <input type="text" name="name" class="form-control" value="{{ old('name',$subject->name ?? '') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">الصورة</label>
    <input type="file" name="image" class="form-control">
    @if(!empty($subject?->image_path))
      <img src="{{ asset('storage/'.$subject->image_path) }}" class="img-thumbnail mt-2" style="height:64px">
    @endif
  </div>

  <div class="col-md-4">
    <label class="form-label">النطاق *</label>
    <select name="scope" class="form-select">
      @foreach(['basic'=>'Basic','clinical'=>'Clinical','both'=>'Both'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('scope',$subject->scope ?? 'basic')==$k)>{{ $k }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">المستوى الأكاديمي</label>
    <input type="text" name="academic_level" class="form-control" value="{{ old('academic_level',$subject->academic_level ?? '') }}">
  </div>
  <div class="col-md-2">
    <label class="form-label">الترتيب</label>
    <input type="number" name="order_index" class="form-control" value="{{ old('order_index',$subject->order_index ?? 0) }}">
  </div>
  <div class="col-md-2">
    <label class="form-label">الحالة *</label>
    <select name="status" class="form-select">
      @foreach(['draft'=>'موقوف','published'=>'مفعل'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('status',$subject->status ?? 'draft')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-1">
      <label class="form-label mb-0">ربط بالأجهزة</label>
      <div>
        <a href="#" id="med-devices-toggle" class="small">تحديد الكل</a>
      </div>
    </div>

    <div class="card">
      <div class="card-body p-2" style="max-height:320px; overflow:auto;">
        <div class="row g-2" id="med-devices-list">
          @foreach(\App\Models\MedDevice::orderBy('name')->get() as $d)
            <div class="col-12 col-md-6 col-lg-4">
              <div class="form-check">
                <input class="form-check-input med-device-checkbox" type="checkbox"
                       name="device_ids[]" value="{{ $d->id }}" id="med_device_{{ $d->id }}"
                       @checked(in_array($d->id,$selected ?? []))>
                <label class="form-check-label" for="med_device_{{ $d->id }}">{{ $d->name }}</label>
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
      const toggle = document.getElementById('med-devices-toggle');
      const container = document.getElementById('med-devices-list');
      if(!toggle || !container) return;

      function setAll(checked){
        container.querySelectorAll('input.med-device-checkbox').forEach(cb => cb.checked = checked);
      }

      function allChecked(){
        const boxes = Array.from(container.querySelectorAll('input.med-device-checkbox'));
        return boxes.length && boxes.every(b => b.checked);
      }

      function refreshLink(){
        toggle.textContent = allChecked() ? 'إلغاء التحديد' : 'تحديد الكل';
      }

      toggle.addEventListener('click', function(e){
        e.preventDefault();
        const shouldCheck = !allChecked();
        setAll(shouldCheck);
        refreshLink();
      });

      container.addEventListener('change', function(e){
        if(e.target && e.target.classList && e.target.classList.contains('med-device-checkbox')){
          refreshLink();
        }
      });

      document.addEventListener('DOMContentLoaded', refreshLink);
      refreshLink();
    })();
  </script>
  @endpush
</div>
