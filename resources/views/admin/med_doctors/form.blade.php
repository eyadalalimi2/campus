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
      @foreach(['draft'=>'مسودة','published'=>'منشور'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('status',$doctor->status ?? 'draft')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Slug *</label>
    <input type="text" name="slug" class="form-control" value="{{ old('slug',$doctor->slug ?? '') }}">
  </div>

  <div class="col-12">
    <label class="form-label">مواد الدكتور</label>
    <select name="subject_ids[]" class="form-select" multiple size="8">
      @foreach(\App\Models\MedSubject::orderBy('name')->get() as $s)
        <option value="{{ $s->id }}" @selected(in_array($s->id,$selected ?? []))>{{ $s->name }}</option>
      @endforeach
    </select>
  </div>
</div>
