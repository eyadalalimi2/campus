<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">الدكتور *</label>
    <select name="doctor_id" class="form-select">
      @foreach(\App\Models\MedDoctor::orderBy('name')->get() as $d)
        <option value="{{ $d->id }}" @selected(old('doctor_id',$video->doctor_id ?? '')==$d->id)>{{ $d->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">المادة *</label>
    <select name="subject_id" class="form-select">
      @foreach(\App\Models\MedSubject::orderBy('name')->get() as $s)
        <option value="{{ $s->id }}" @selected(old('subject_id',$video->subject_id ?? '')==$s->id)>{{ $s->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">الموضوع (اختياري)</label>
    <select name="topic_id" class="form-select">
      <option value="">— لا يوجد —</option>
      @foreach(\App\Models\MedTopic::orderBy('title')->get() as $t)
        <option value="{{ $t->id }}" @selected(old('topic_id',$video->topic_id ?? '')==$t->id)>{{ $t->title }}</option>
      @endforeach
    </select>
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
    <label class="form-label">تاريخ النشر</label>
    <input type="datetime-local" name="published_at" class="form-control"
           value="{{ old('published_at', isset($video->published_at)?$video->published_at->format('Y-m-d\TH:i'):'' ) }}">
  </div>
</div>
