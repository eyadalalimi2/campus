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
      <div class="d-flex align-items-center gap-2">
        <strong id="subject-count" class="me-2">0</strong>
        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#deviceSubjectPickerModal">اختيار المواد</button>
      </div>
    </div>

    <div class="card">
      <div class="card-body p-2">
        <input type="text" id="subject_display" class="form-control form-control-sm" readonly placeholder="لم يتم اختيار مواد">
      </div>
    </div>

    <!-- Modal for selecting subjects -->
    <div class="modal fade" id="deviceSubjectPickerModal" tabindex="-1" aria-labelledby="deviceSubjectPickerModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deviceSubjectPickerModalLabel">اختيار المواد المرتبطة</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="d-flex gap-2 align-items-center mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="device-modal-subject-select-all-toggle">
                <label class="form-check-label" for="device-modal-subject-select-all-toggle">اختيار الكل</label>
              </div>
              <button type="button" id="device-modal-subject-select-all" class="btn btn-sm btn-outline-primary">تحديد الكل</button>
              <button type="button" id="device-modal-subject-clear-all" class="btn btn-sm btn-outline-secondary">مسح الكل</button>
              <input id="device-modal-subject-filter" class="form-control form-control-sm ms-auto" style="max-width:360px" placeholder="بحث...">
            </div>

            <style>
              /* Grid: 10 items per row on md+, 2 per row on small screens */
              #device-modal-subject-grid{ display:flex; flex-wrap:wrap; gap:.25rem; }
              .device-modal-subject-item{ box-sizing:border-box; padding:.25rem; }
              .device-modal-subject-item .form-check{ margin:0; }
              @media(min-width:768px){ .device-modal-subject-item{ flex:0 0 10%; max-width:10%; } }
              @media(max-width:767.98px){ .device-modal-subject-item{ flex:0 0 50%; max-width:50%; } }
            </style>

            <div id="device-modal-subject-grid" aria-hidden="false">
              @foreach(\App\Models\MedSubject::orderBy('name')->get() as $s)
                <div class="device-modal-subject-item">
                  <div class="form-check">
                    <input class="form-check-input device-modal-subject-checkbox" type="checkbox" id="device_modal_subject_{{ $s->id }}" name="subject_ids[]" value="{{ $s->id }}" @checked(in_array($s->id, $selected ?? []))>
                    <label class="form-check-label" for="device_modal_subject_{{ $s->id }}">{{ $s->name }}</label>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
          </div>
        </div>
      </div>
    </div>

    @push('scripts')
    <script>
      (function(){
        // Main display elements
        const subjectDisplay = document.getElementById('subject_display');
        const subjectCount = document.getElementById('subject-count');

        // Modal elements
        const modal = document.getElementById('deviceSubjectPickerModal');
        const modalGrid = document.getElementById('device-modal-subject-grid');
        const modalFilter = document.getElementById('device-modal-subject-filter');
        const modalBtnAll = document.getElementById('device-modal-subject-select-all');
        const modalBtnClear = document.getElementById('device-modal-subject-clear-all');
        const modalToggle = document.getElementById('device-modal-subject-select-all-toggle');

        if(!modalGrid) return;

        const modalCheckboxes = () => Array.from(modalGrid.querySelectorAll('.device-modal-subject-checkbox'));

        function syncMainDisplay(){
          const selected = modalCheckboxes().filter(cb => cb.checked);
          subjectCount.textContent = selected.length;
          subjectDisplay.value = selected.map(cb => cb.closest('.form-check')?.textContent?.trim()).filter(Boolean).join(', ');
          if(subjectDisplay.value === '') subjectDisplay.placeholder = 'لم يتم اختيار مواد';
        }

        function filterModalSubjects(){
          const q = modalFilter.value.trim().toLowerCase();
          modalCheckboxes().forEach(cb => {
            const item = cb.closest('.device-modal-subject-item');
            const label = (cb.nextElementSibling?.textContent || '').toLowerCase();
            item.style.display = (q === '' || label.indexOf(q) !== -1) ? '' : 'none';
          });
        }

        modalBtnAll.addEventListener('click', ()=>{
          modalCheckboxes().forEach(cb=>{ const item = cb.closest('.device-modal-subject-item'); if(item.style.display !== 'none') cb.checked = true; });
          if(modalToggle) modalToggle.checked = true;
          syncMainDisplay();
        });

        modalBtnClear.addEventListener('click', ()=>{
          modalCheckboxes().forEach(cb=> cb.checked = false);
          if(modalToggle) modalToggle.checked = false;
          syncMainDisplay();
        });

        modalToggle.addEventListener('change', ()=>{
          const v = modalToggle.checked;
          modalCheckboxes().forEach(cb=>{ const item = cb.closest('.device-modal-subject-item'); if(item.style.display !== 'none') cb.checked = v; });
          syncMainDisplay();
        });

        modalFilter.addEventListener('input', ()=>{ filterModalSubjects(); });

        modalGrid.addEventListener('change', (e)=>{
          if(e.target && e.target.classList && e.target.classList.contains('device-modal-subject-checkbox')) syncMainDisplay();
        });

        // Focus filter when modal shown and sync display
        modal.addEventListener('shown.bs.modal', function(){ if(modalFilter) modalFilter.focus(); syncMainDisplay(); });

        // Initial sync for server-prechecked items
        document.addEventListener('DOMContentLoaded', function(){ syncMainDisplay(); }, { once: true });
        syncMainDisplay();
      })();
    </script>
    @endpush
  </div>
