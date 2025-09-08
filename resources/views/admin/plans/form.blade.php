@php $isEdit = isset($plan) && $plan->exists; @endphp

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">اسم الخطة <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required
           value="{{ old('name', $plan->name ?? '') }}">
  </div>

  <div class="col-md-3">
    <label class="form-label">المدة بالأيام <span class="text-danger">*</span></label>
    <input type="number" name="duration_days" class="form-control" min="1" max="3650" required
           value="{{ old('duration_days', $plan->duration_days ?? 365) }}">
  </div>

  <div class="col-md-3 d-flex align-items-end">
    <div class="form-check">
      <input type="hidden" name="is_active" value="0">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
             {{ old('is_active', $plan->is_active ?? true) ? 'checked' : '' }}>
      <label class="form-check-label" for="is_active">مفعل</label>
    </div>
  </div>

  <div class="col-12">
    <label class="form-label">الوصف (اختياري)</label>
    <textarea name="description" class="form-control" rows="3">{{ old('description', $plan->description ?? '') }}</textarea>
  </div>
</div>
