@csrf
<div class="mb-3">
    <label for="name" class="form-label">اسم المجال</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $discipline->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
        {{ old('is_active', $discipline->is_active ?? 1) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">نشط؟</label>
</div>

<button type="submit" class="btn btn-success">حفظ</button>
