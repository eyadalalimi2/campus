<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">الدكتور *</label>
    <div class="input-group">
      <input type="text" id="doctor_display" class="form-control" value="{{ old('doctor_name', isset($video) && $video->doctor ? $video->doctor->name : '') }}" readonly>
      <input type="hidden" name="doctor_id" id="doctor_id" value="{{ old('doctor_id',$video->doctor_id ?? '') }}">
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#doctorPickerModal">اختيار</button>
    </div>
  </div>

  <div class="col-md-4">
    <label class="form-label">المادة *</label>
    <div class="input-group">
      <input type="text" id="subject_display" class="form-control" value="{{ old('subject_name', isset($video) && $video->subject ? $video->subject->name : '') }}" readonly>
      <input type="hidden" name="subject_id" id="subject_id" value="{{ old('subject_id',$video->subject_id ?? '') }}">
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#subjectPickerModal">اختيار</button>
    </div>
  </div>

  <div class="col-md-4">
    <label class="form-label">الموضوع (اختياري)</label>
    <div class="input-group">
      <input type="text" id="topic_display" class="form-control" value="{{ old('topic_name', isset($video) && $video->topic ? $video->topic->title : '') }}" readonly>
      <input type="hidden" name="topic_id" id="topic_id" value="{{ old('topic_id',$video->topic_id ?? '') }}">
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#topicPickerModal">اختيار</button>
      <button type="button" id="topic_clear" class="btn btn-outline-danger" title="مسح الاختيار">×</button>
    </div>
  </div>

  <!-- Doctor picker modal -->
  <div class="modal fade" id="doctorPickerModal" tabindex="-1" aria-labelledby="doctorPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="doctorPickerModalLabel">اختيار الدكتور</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <input type="search" id="doctor_search" class="form-control" placeholder="ابحث عن دكتور بالاسم...">
          </div>
          <div class="list-group" id="doctors_list" style="max-height:50vh; overflow:auto;">
            @foreach(\App\Models\MedDoctor::orderBy('name')->get() as $d)
              <button type="button" class="list-group-item list-group-item-action doctor-item @if(old('doctor_id',$video->doctor_id ?? '') == $d->id) active @endif" data-id="{{ $d->id }}" data-name="{{ $d->name }}">{{ $d->name }}</button>
            @endforeach
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
        </div>
      </div>
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
              <button type="button" class="list-group-item list-group-item-action subject-item @if(old('subject_id',$video->subject_id ?? '') == $s->id) active @endif" data-id="{{ $s->id }}" data-name="{{ $s->name }}">{{ $s->name }}</button>
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
              <button type="button" class="list-group-item list-group-item-action topic-item @if(old('topic_id',$video->topic_id ?? '') == $t->id) active @endif" data-id="{{ $t->id }}" data-name="{{ $t->title }}">{{ $t->title }}</button>
            @endforeach
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <label class="form-label">العنوان *</label>
    <input type="text" name="title" class="form-control" value="{{ old('title',$video->title ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">صورة مصغّرة (Upload)</label>
    <input type="file" name="thumbnail" class="form-control">
  </div>

  <div class="col-md-6">
    <label class="form-label">رابط صورة مصغّرة (URL)</label>
    <input type="url" name="thumbnail_url" class="form-control" value="{{ old('thumbnail_url',$video->thumbnail_url ?? '') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">رابط يوتيوب *</label>
    <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url',$video->youtube_url ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">الترتيب</label>
    <input type="number" name="order_index" class="form-control" value="{{ old('order_index',$video->order_index ?? 0) }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">الحالة *</label>
    <select name="status" class="form-select">
      @foreach(['draft'=>'موقوف','published'=>'مفعل'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('status',$video->status ?? 'draft')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    @if(!isset($video) || empty($video->id))
      {{-- hide published_at on create page: keep value as hidden field so submission stays consistent --}}
      <input type="hidden" name="published_at" value="{{ old('published_at', '') }}">
    @else
      <label class="form-label">تاريخ النشر</label>
      <input type="datetime-local" name="published_at" class="form-control"
             value="{{ old('published_at', isset($video->published_at)?$video->published_at->format('Y-m-d\TH:i'):'' ) }}">
    @endif
  </div>

@push('scripts')
<script>
  (function(){
    // Generic picker handler factory
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

        // mark active
        Array.prototype.forEach.call(list.children, function(it){ it.classList.remove('active'); });
        target.classList.add('active');

        // hide modal
        try{ var bs = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal); bs.hide(); }catch(err){}
      });

      if(search) search.addEventListener('input', filter);
      if(opts.clearBtnId){
        var clearBtn = document.getElementById(opts.clearBtnId);
        if(clearBtn) clearBtn.addEventListener('click', function(){ hidden.value=''; display.value=''; Array.prototype.forEach.call(list.children, function(it){ it.classList.remove('active'); }); });
      }

      // focus search when modal shown
      if(modal && search) modal.addEventListener('shown.bs.modal', function(){ search.focus(); });
    }

    // Doctor picker
    makePicker({
      searchId: 'doctor_search', listId: 'doctors_list', displayId: 'doctor_display', hiddenId: 'doctor_id', modalId: 'doctorPickerModal', itemClass: 'doctor-item'
    });

    // Subject picker
    makePicker({
      searchId: 'subject_search', listId: 'subjects_list', displayId: 'subject_display', hiddenId: 'subject_id', modalId: 'subjectPickerModal', itemClass: 'subject-item'
    });

    // Topic picker (includes clear)
    makePicker({
      searchId: 'topic_search', listId: 'topics_list', displayId: 'topic_display', hiddenId: 'topic_id', modalId: 'topicPickerModal', itemClass: 'topic-item', clearBtnId: 'topic_clear_modal'
    });

    // Local clear button for topic (small × next to display)
    var topicClear = document.getElementById('topic_clear');
    if(topicClear) topicClear.addEventListener('click', function(){ document.getElementById('topic_id').value=''; document.getElementById('topic_display').value=''; });

  })();
</script>
@endpush
</div>
