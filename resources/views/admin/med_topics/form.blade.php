<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">المادة *</label>
    <div class="input-group">
      <input type="text" id="subject_display" class="form-control" value="{{ old('subject_name', isset($topic) && $topic->subject ? $topic->subject->name : '') }}" readonly>
      <input type="hidden" name="subject_id" id="subject_id" value="{{ old('subject_id',$topic->subject_id ?? '') }}">
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#subjectModal">اختيار</button>
    </div>
    <div class="form-text text-muted">اضغط "اختيار" لفتح نافذة اختيار المادة</div>
  </div>
  <div class="col-md-6">
    <label class="form-label">العنوان *</label>
    <input type="text" name="title" class="form-control" value="{{ old('title',$topic->title ?? '') }}">
  </div>
  <div class="col-12">
    <label class="form-label">الوصف</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description',$topic->description ?? '') }}</textarea>
  </div>
  <div class="col-md-2">
    <label class="form-label">الترتيب</label>
    <input type="number" name="order_index" class="form-control" value="{{ old('order_index',$topic->order_index ?? 0) }}">
  </div>
  <div class="col-md-2">
    <label class="form-label">الحالة *</label>
    <select name="status" class="form-select">
      @foreach(['draft'=>'موقوف','published'=>'مفعل'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('status',$topic->status ?? 'draft')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>
</div>

<!-- Subject Picker Modal -->
<div class="modal fade" id="subjectModal" tabindex="-1" aria-labelledby="subjectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="subjectModalLabel">اختيار المادة</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <input type="search" id="subject_search" class="form-control" placeholder="ابحث عن مادة بالاسم...">
        </div>
        <div class="list-group" id="subjects_list" style="max-height:50vh; overflow:auto;">
          @foreach(\App\Models\MedSubject::orderBy('name')->get() as $s)
            <button type="button" class="list-group-item list-group-item-action subject-item @if(old('subject_id',$topic->subject_id ?? '') == $s->id) active @endif" data-id="{{ $s->id }}" data-name="{{ $s->name }}">
              {{ $s->name }}
            </button>
          @endforeach
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>
    </div>
  </div>
</div>

<style>
.subject-item.selected { background:#0d6efd; color:#fff; }
</style>

<script>
  (function(){
    // Elements
    var search = document.getElementById('subject_search');
    var list = document.getElementById('subjects_list');
    var display = document.getElementById('subject_display');
    var hidden = document.getElementById('subject_id');
    var modalEl = document.getElementById('subjectModal');

    // Filter subjects by name
    function filterSubjects(){
      var q = search.value.trim().toLowerCase();
      Array.prototype.forEach.call(list.children, function(item){
        var name = (item.getAttribute('data-name')||'').toLowerCase();
        if(q === '' || name.indexOf(q) !== -1){
          item.classList.remove('d-none');
        } else {
          item.classList.add('d-none');
        }
      });
    }

    // Click handler to pick a subject
    function onPick(e){
      var target = e.target;
      if(!target.classList.contains('subject-item')) return;
      var id = target.getAttribute('data-id');
      var name = target.getAttribute('data-name');
      if(!id) return;
      hidden.value = id;
      display.value = name;

      // mark active
      Array.prototype.forEach.call(list.children, function(item){ item.classList.remove('active'); });
      target.classList.add('active');

      // hide modal (Bootstrap)
      try{
        var bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
        bsModal.hide();
      }catch(err){
        // fallback: just hide
        modalEl.classList.remove('show');
        modalEl.style.display = 'none';
        document.body.classList.remove('modal-open');
      }
    }

    if(search){ search.addEventListener('input', filterSubjects); }
    if(list){ list.addEventListener('click', onPick); }

    // Optional: focus search when modal opened
    if(modalEl){
      modalEl.addEventListener('shown.bs.modal', function(){
        if(search) search.focus();
      });
    }
  })();
</script>
