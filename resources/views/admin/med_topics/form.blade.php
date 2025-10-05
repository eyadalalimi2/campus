<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">المادة *</label>
    <select name="subject_id" class="form-select">
      @foreach(\App\Models\MedSubject::orderBy('name')->get() as $s)
        <option value="{{ $s->id }}" @selected(old('subject_id',$topic->subject_id ?? '')==$s->id)>{{ $s->name }}</option>
      @endforeach
    </select>
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
