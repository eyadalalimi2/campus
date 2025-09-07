@csrf

<div class="mb-3">
    <label for="name_ar" class="form-label">اسم الدولة بالعربية</label>
    <input type="text" name="name_ar" id="name_ar" class="form-control @error('name_ar') is-invalid @enderror"
           value="{{ old('name_ar', $country->name_ar ?? '') }}" required>
    @error('name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="iso2" class="form-label">رمز ISO2 (اختياري)</label>
    <input type="text" name="iso2" id="iso2" class="form-control @error('iso2') is-invalid @enderror"
           value="{{ old('iso2', $country->iso2 ?? '') }}" maxlength="2" placeholder="مثلاً: YE">
    @error('iso2') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="phone_code" class="form-label">رمز الهاتف (اختياري)</label>
    <input type="text" name="phone_code" id="phone_code" class="form-control @error('phone_code') is-invalid @enderror"
           value="{{ old('phone_code', $country->phone_code ?? '') }}" placeholder="مثلاً: +967">
    @error('phone_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="currency_code" class="form-label">رمز العملة (اختياري)</label>
    <input type="text" name="currency_code" id="currency_code" class="form-control @error('currency_code') is-invalid @enderror"
           value="{{ old('currency_code', $country->currency_code ?? '') }}" maxlength="3" placeholder="مثلاً: YER">
    @error('currency_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
        {{ old('is_active', $country->is_active ?? 1) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">نشط؟</label>
</div>

<button type="submit" class="btn btn-success">حفظ</button>
