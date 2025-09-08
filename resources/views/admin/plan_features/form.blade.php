@csrf
<div class="mb-3">
  <label class="form-label">المفتاح <span class="text-danger">*</span></label>
  <input type="text" name="feature_key" class="form-control"
         value="{{ old('feature_key', $feature->feature_key ?? '') }}" required maxlength="100">
</div>
<div class="mb-3">
  <label class="form-label">القيمة</label>
  <textarea name="feature_value" class="form-control" rows="3">{{ old('feature_value', $feature->feature_value ?? '') }}</textarea>
</div>
<button class="btn btn-success">حفظ</button>
