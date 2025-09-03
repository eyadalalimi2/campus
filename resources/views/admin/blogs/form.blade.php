@php $isEdit = isset($blog); @endphp
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">العنوان</label>
    <input type="text" name="title" class="form-control" required value="{{ old('title',$blog->title ?? '') }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">السلَج (Slug)</label>
    <input type="text" name="slug" class="form-control" required value="{{ old('slug',$blog->slug ?? '') }}" placeholder="news-1">
  </div>

  <div class="col-12">
    <label class="form-label">ملخص (اختياري)</label>
    <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt',$blog->excerpt ?? '') }}</textarea>
  </div>

  <div class="col-12">
    <label class="form-label">المحتوى</label>
    <textarea name="body" class="form-control" rows="6">{{ old('body',$blog->body ?? '') }}</textarea>
  </div>

  <div class="col-md-4">
    <label class="form-label">الحالة</label>
    @php $st = old('status',$blog->status ?? 'draft'); @endphp
    <select name="status" class="form-select">
      <option value="draft" @selected($st==='draft')>مسودة</option>
      <option value="published" @selected($st==='published')>منشورة</option>
      <option value="archived" @selected($st==='archived')>مؤرشفة</option>
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">تاريخ النشر</label>
    <input type="datetime-local" name="published_at" class="form-control"
      value="{{ old('published_at', isset($blog->published_at) ? $blog->published_at->format('Y-m-d\TH:i') : '') }}">
  </div>
  <div class="col-md-4 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="b_active"
             {{ old('is_active',$blog->is_active ?? true) ? 'checked':'' }}>
      <label class="form-check-label" for="b_active">مفعل</label>
    </div>
  </div>

  <div class="col-md-6">
    <label class="form-label">الجامعة (اختياري)</label>
    <select name="university_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\University::orderBy('name')->get() as $u)
        <option value="{{ $u->id }}" @selected(old('university_id',$blog->university_id ?? '')==$u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">الدكتور (اختياري)</label>
    <select name="doctor_id" class="form-select">
      <option value="">— اختر —</option>
      @foreach(\App\Models\Doctor::orderBy('name')->get() as $d)
        <option value="{{ $d->id }}" @selected(old('doctor_id',$blog->doctor_id ?? '')==$d->id)>{{ $d->name }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label">صورة الغلاف (PNG/JPG)</label>
    <input type="file" name="cover_image" class="form-control">
    @if(!empty($blog?->cover_image_path))
      <img src="{{ asset('storage/'.$blog->cover_image_path) }}" class="mt-2 rounded" style="height:80px">
    @endif
  </div>
</div>
