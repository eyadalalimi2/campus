<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">الاسم *</label>
    <input type="text" name="name" class="form-control" value="{{ old('name',$doctor->name ?? '') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">الصورة (Avatar)</label>
    <input type="file" name="avatar" class="form-control">
    @if(!empty($doctor?->avatar_path))
      <img src="{{ asset('storage/'.$doctor->avatar_path) }}" class="img-thumbnail mt-2" style="height:64px">
    @endif
  </div>
  <div class="col-12">
    <label class="form-label">نبذة</label>
    <textarea name="bio" class="form-control" rows="3">{{ old('bio',$doctor->bio ?? '') }}</textarea>
  </div>
  <div class="col-md-3">
    <label class="form-label">الترتيب</label>
    <input type="number" name="order_index" class="form-control" value="{{ old('order_index',$doctor->order_index ?? 0) }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">الحالة *</label>
    <select name="status" class="form-select">
      @foreach(['draft'=>'موقوف','published'=>'مفعل'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('status',$doctor->status ?? 'draft')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>
  

  <div class="col-12">
    <label class="form-label">مواد الدكتور</label>
    <div id="med-subjects-wrapper" class="border rounded p-2 d-flex flex-column">
      <div class="d-flex gap-2 align-items-center mb-2">
        <div>
          <strong id="subject-count" class="me-2">0</strong>
          <span class="text-muted">مادة/مواد محددة</span>
        </div>
        <input type="text" id="subject_display" class="form-control form-control-sm ms-2" readonly placeholder="لم يتم اختيار مواد" style="max-width:320px">
        <button type="button" class="btn btn-sm btn-outline-secondary ms-auto" data-bs-toggle="modal" data-bs-target="#subjectPickerModal">اختيار المواد</button>
      </div>
      <div class="text-muted small">اضغط "اختيار المواد" لفتح نافذة البحث والاختيار المتعدد.</div>
    </div>

    <!-- Modal: Doctor Subjects Picker -->
    <div class="modal fade" id="subjectPickerModal" tabindex="-1" aria-labelledby="subjectPickerModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="subjectPickerModalLabel">اختيار مواد الدكتور</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="d-flex gap-2 align-items-center mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="modal-subject-select-all-toggle">
                <label class="form-check-label" for="modal-subject-select-all-toggle">اختيار الكل</label>
              </div>
              <button type="button" id="modal-subject-select-all" class="btn btn-sm btn-outline-primary">تحديد الكل</button>
              <button type="button" id="modal-subject-clear-all" class="btn btn-sm btn-outline-secondary">مسح الكل</button>
              <input id="modal-subject-filter" class="form-control form-control-sm ms-auto" style="max-width:360px" placeholder="بحث...">
            </div>

            <style>
              /* Grid: 10 items per row on md+, 2 per row on small screens */
              #modal-subject-grid{ display:flex; flex-wrap:wrap; gap:.25rem; }
              .modal-subject-item{ box-sizing:border-box; padding:.25rem; }
              .modal-subject-item .form-check{ margin:0; }
              @media(min-width:768px){ .modal-subject-item{ flex:0 0 10%; max-width:10%; } }
              @media(max-width:767.98px){ .modal-subject-item{ flex:0 0 50%; max-width:50%; } }
            </style>

            <div id="modal-subject-grid" aria-hidden="false">
              @foreach(\App\Models\MedSubject::orderBy('name')->get() as $s)
                <div class="modal-subject-item">
                  <div class="form-check">
                    <input class="form-check-input modal-subject-checkbox" type="checkbox" id="modal_subject_{{ $s->id }}" name="subject_ids[]" value="{{ $s->id }}" @checked(in_array($s->id,$selected ?? []))>
                    <label class="form-check-label" for="modal_subject_{{ $s->id }}">{{ $s->name }}</label>
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
  </div>
</div>

@push('scripts')
<script>
  (function(){
    // Elements in main form
    const mainWrapper = document.getElementById('med-subjects-wrapper');
    const subjectDisplay = document.getElementById('subject_display');
    const subjectCountBadge = document.getElementById('subject-count');

    // Elements in modal
    const modal = document.getElementById('subjectPickerModal');
    const modalGrid = document.getElementById('modal-subject-grid');
    const modalCheckboxes = () => Array.from(modalGrid.querySelectorAll('.modal-subject-checkbox'));
    const modalToggle = document.getElementById('modal-subject-select-all-toggle');
    const modalBtnAll = document.getElementById('modal-subject-select-all');
    const modalBtnClear = document.getElementById('modal-subject-clear-all');
    const modalFilter = document.getElementById('modal-subject-filter');

    if(!modalGrid) return;

    // Update main display from current modal selections
    function syncMainDisplay(){
      const selected = modalCheckboxes().filter(cb => cb.checked);
      subjectCountBadge.textContent = selected.length;
      subjectDisplay.value = selected.map(cb => cb.closest('.form-check')?.textContent?.trim()).filter(Boolean).join(', ');
      if(subjectDisplay.value === '') subjectDisplay.placeholder = 'لم يتم اختيار مواد';
    }

    // Filter subjects by name inside modal
    function filterModalSubjects(){
      const q = modalFilter.value.trim().toLowerCase();
      modalCheckboxes().forEach(cb => {
        const item = cb.closest('.modal-subject-item');
        const label = (cb.nextElementSibling?.textContent || '').toLowerCase();
        item.style.display = (q === '' || label.indexOf(q) !== -1) ? '' : 'none';
      });
    }

    // Select all visible
    modalBtnAll.addEventListener('click', ()=>{
      modalCheckboxes().forEach(cb=>{ const item = cb.closest('.modal-subject-item'); if(item.style.display !== 'none') cb.checked = true; });
      syncMainDisplay();
    });

    modalBtnClear.addEventListener('click', ()=>{
      modalCheckboxes().forEach(cb=> cb.checked = false);
      if(modalToggle) modalToggle.checked = false;
      syncMainDisplay();
    });

    modalToggle.addEventListener('change', ()=>{
      const v = modalToggle.checked;
      modalCheckboxes().forEach(cb=>{ const item = cb.closest('.modal-subject-item'); if(item.style.display !== 'none') cb.checked = v; });
      syncMainDisplay();
    });

    modalFilter.addEventListener('input', ()=>{ filterModalSubjects(); });

    // Update main display when any modal checkbox changes
    modalGrid.addEventListener('change', (e)=>{
      if(e.target && e.target.classList && e.target.classList.contains('modal-subject-checkbox')) syncMainDisplay();
    });

    // When modal opens, focus search and ensure display sync
    modal.addEventListener('shown.bs.modal', function(){
      if(modalFilter) modalFilter.focus();
      syncMainDisplay();
    });

    // On DOM ready, initial sync (pre-checked items from server)
    document.addEventListener('DOMContentLoaded', function(){ syncMainDisplay(); }, { once: true });
    syncMainDisplay();
  })();
</script>
@endpush
