@php($item = $item ?? null)

<div class="row g-3">
  <div class="col-12">
    <label class="form-label">العنوان</label>
    <input type="text" name="title" value="{{ old('title', $item->title ?? '') }}" class="form-control" required>
    @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="col-12">
    <label class="form-label">الوصف</label>
    <textarea name="description" rows="4" class="form-control" placeholder="وصف مختصر أو خطوات الدراسة">{{ old('description', $item->description ?? '') }}</textarea>
    @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="col-12">
    <label class="form-label">رابط فيديو يوتيوب</label>
    <input type="url" name="youtube_url" value="{{ old('youtube_url', $item->youtube_url ?? '') }}" class="form-control" required>
    @error('youtube_url')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
</div>
