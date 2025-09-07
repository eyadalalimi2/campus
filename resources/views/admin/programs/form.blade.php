@csrf
<div class="mb-3">
    <label for="discipline_id" class="form-label">المجال</label>
    <select name="discipline_id" id="discipline_id" class="form-select @error('discipline_id') is-invalid @enderror">
        <option value="">-- اختر المجال --</option>
        @foreach ($disciplines as $disc)
            <option value="{{ $disc->id }}" {{ old('discipline_id', $program->discipline_id ?? '') == $disc->id ? 'selected' : '' }}>
                {{ $disc->name }}
            </option>
        @endforeach
    </select>
    @error('discipline_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="name" class="form-label">اسم البرنامج</label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $program->name ?? '') }}" required>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
        {{ old('is_active', $program->is_active ?? 1) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">نشط؟</label>
</div>

<button type="submit" class="btn btn-success">حفظ</button>
