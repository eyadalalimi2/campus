<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">المادة *</label>
    <select name="subject_id" class="form-select">
      @foreach(\App\Models\MedSubject::orderBy('name')->get() as $s)
        <option value="{{ $s->id }}" @selected(old('subject_id',$resource->subject_id ?? '')==$s->id)>{{ $s->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">الموضوع (اختياري)</label>
    <select name="topic_id" class="form-select">
      <option value="">— لا يوجد —</option>
      @foreach(\App\Models\MedTopic::orderBy('title')->get() as $t)
        <option value="{{ $t->id }}" @selected(old('topic_id',$resource->topic_id ?? '')==$t->id)>{{ $t->title }}</option>
      @endforeach
    </select>
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
