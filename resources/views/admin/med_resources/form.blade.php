<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">المادة *</label>
    <div class="input-group">
      <input type="text" id="subject_display" class="form-control" value="{{ old('subject_name', isset($resource) && $resource->subject ? $resource->subject->name : '') }}" readonly>
      <input type="hidden" name="subject_id" id="subject_id" value="{{ old('subject_id',$resource->subject_id ?? '') }}">
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#subjectPickerModal">اختيار</button>
    </div>
  </div>
  <div class="col-md-4">
    <label class="form-label">الموضوع (اختياري)</label>
    <div class="input-group">
      <input type="text" id="topic_display" class="form-control" value="{{ old('topic_name', isset($resource) && $resource->topic ? $resource->topic->title : '') }}" readonly>
      <input type="hidden" name="topic_id" id="topic_id" value="{{ old('topic_id',$resource->topic_id ?? '') }}">
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#topicPickerModal">اختيار</button>
      <button type="button" id="topic_clear" class="btn btn-outline-danger" title="مسح الاختيار">×</button>
    </div>
  </div>
  <div class="col-md-4">
    <label class="form-label">التصنيف *</label>
    <select name="category_id" class="form-select">
      @foreach(\App\Models\MedResourceCategory::orderBy('order_index')->get() as $c)
        <option value="{{ $c->id }}" @selected(old('category_id',$resource->category_id ?? '')==$c->id)>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-8">
    <label class="form-label">العنوان *</label>
    <input type="text" name="title" class="form-control" value="{{ old('title',$resource->title ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">رفع PDF</label>
    <input type="file" name="file" class="form-control">
  </div>

  <div class="col-md-6">
    <label class="form-label">رابط ملف (URL)</label>
    <input type="url" name="file_url" class="form-control" value="{{ old('file_url',$resource->file_url ?? '') }}">
    @if(!empty($resource?->file_url))
      <a class="d-inline-block mt-2" target="_blank" href="{{ $resource->file_url }}"><i class="bi bi-box-arrow-up-right"></i> فتح الملف الحالي</a>
    @endif
  </div>
  <div class="col-md-3">
    <label class="form-label">حجم الملف (بايت)</label>
    <input type="number" name="file_size_bytes" class="form-control" value="{{ old('file_size_bytes',$resource->file_size_bytes ?? '') }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">عدد الصفحات</label>
    <input type="number" name="pages_count" class="form-control" value="{{ old('pages_count',$resource->pages_count ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">الترتيب</label>
    <input type="number" name="order_index" class="form-control" value="{{ old('order_index',$resource->order_index ?? 0) }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">الحالة *</label>
    <select name="status" class="form-select">
      @foreach(['draft'=>'موقوف','published'=>'مفعل'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('status',$resource->status ?? 'draft')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-12">
    <label class="form-label">الوصف</label>
    <textarea name="description" rows="3" class="form-control">{{ old('description',$resource->description ?? '') }}</textarea>
  </div>
</div>

<!-- Subject picker modal -->
<div class="modal fade" id="subjectPickerModal" tabindex="-1" aria-labelledby="subjectPickerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="subjectPickerModalLabel">اختيار المادة</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <input type="search" id="subject_search" class="form-control" placeholder="ابحث عن مادة بالاسم...">
        </div>
        <div class="list-group" id="subjects_list" style="max-height:50vh; overflow:auto;">
          @foreach(\App\Models\MedSubject::orderBy('name')->get() as $s)
            <button type="button" class="list-group-item list-group-item-action subject-item @if(old('subject_id',$resource->subject_id ?? '') == $s->id) active @endif" data-id="{{ $s->id }}" data-name="{{ $s->name }}">{{ $s->name }}</button>
          @endforeach
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
      </div>
    </div>
  </div>
</div>

<!-- Topic picker modal -->
<div class="modal fade" id="topicPickerModal" tabindex="-1" aria-labelledby="topicPickerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="topicPickerModalLabel">اختيار الموضوع (اختياري)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3 d-flex gap-2">
          <input type="search" id="topic_search" class="form-control" placeholder="ابحث عن موضوع بالعنوان...">
          <button type="button" id="topic_clear_modal" class="btn btn-outline-danger">مسح</button>
        </div>
        <div class="list-group" id="topics_list" style="max-height:50vh; overflow:auto;">
          <button type="button" class="list-group-item list-group-item-action topic-item" data-id="" data-name="— لا يوجد —">— لا يوجد —</button>
          @foreach(\App\Models\MedTopic::orderBy('title')->get() as $t)
            <button type="button" class="list-group-item list-group-item-action topic-item @if(old('topic_id',$resource->topic_id ?? '') == $t->id) active @endif" data-id="{{ $t->id }}" data-name="{{ $t->title }}">{{ $t->title }}</button>
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
    function makePicker(opts){
      var search = document.getElementById(opts.searchId);
      var list = document.getElementById(opts.listId);
      var display = document.getElementById(opts.displayId);
      var hidden = document.getElementById(opts.hiddenId);
      var modal = document.getElementById(opts.modalId);
      if(!list || !display || !hidden) return;

      function filter(){
        var q = (search && search.value || '').trim().toLowerCase();
        Array.prototype.forEach.call(list.children, function(item){
          var name = (item.getAttribute('data-name')||'').toLowerCase();
          if(q === '' || name.indexOf(q) !== -1) item.classList.remove('d-none'); else item.classList.add('d-none');
        });
      }

      list.addEventListener('click', function(e){
        var target = e.target;
        if(!target || !target.classList.contains(opts.itemClass)) return;
        var id = target.getAttribute('data-id');
        var name = target.getAttribute('data-name');
        hidden.value = id || '';
        display.value = name || '';
        Array.prototype.forEach.call(list.children, function(it){ it.classList.remove('active'); });
        target.classList.add('active');
        try{ var bs = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal); bs.hide(); }catch(err){}
      });

      if(search) search.addEventListener('input', filter);
      if(opts.clearBtnId){ var clearBtn = document.getElementById(opts.clearBtnId); if(clearBtn) clearBtn.addEventListener('click', function(){ hidden.value=''; display.value=''; Array.prototype.forEach.call(list.children, function(it){ it.classList.remove('active'); }); }); }
      if(modal && search) modal.addEventListener('shown.bs.modal', function(){ search.focus(); });
    }

    makePicker({ searchId: 'subject_search', listId: 'subjects_list', displayId: 'subject_display', hiddenId: 'subject_id', modalId: 'subjectPickerModal', itemClass: 'subject-item' });
    makePicker({ searchId: 'topic_search', listId: 'topics_list', displayId: 'topic_display', hiddenId: 'topic_id', modalId: 'topicPickerModal', itemClass: 'topic-item', clearBtnId: 'topic_clear_modal' });

    var topicClear = document.getElementById('topic_clear'); if(topicClear) topicClear.addEventListener('click', function(){ document.getElementById('topic_id').value=''; document.getElementById('topic_display').value=''; });
  })();
</script>
@endpush
