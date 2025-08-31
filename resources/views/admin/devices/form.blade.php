@php $isEdit = isset($device); @endphp
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">المادة</label>
    <select name="material_id" class="form-select" required>
      <option value="">— اختر —</option>
      @foreach(\App\Models\Material::orderBy('name')->get() as $m)
        <option value="{{ $m->id }}" @selected(old('material_id',$device->material_id ?? '')==$m->id)>{{ $m->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4">
    <label class="form-label">الاسم</label>
    <input type="text" name="name" class="form-control" required value="{{ old('name',$device->name ?? '') }}">
  </div>
  <div class="col-md-2">
    <label class="form-label">الكود</label>
    <input type="text" name="code" class="form-control" value="{{ old('code',$device->code ?? '') }}">
  </div>
  <div class="col-12">
    <label class="form-label">الوصف (اختياري)</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description',$device->description ?? '') }}</textarea>
  </div>
  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active',$device->is_active ?? true) ? 'checked':'' }}>
      <label class="form-check-label" for="is_active">مفعل</label>
    </div>
  </div>
</div>
