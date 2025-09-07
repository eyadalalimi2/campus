@csrf
<div class="mb-3">
    <label for="university_id" class="form-label">الجامعة</label>
    <select name="university_id" id="university_id" class="form-select @error('university_id') is-invalid @enderror">
        <option value="">-- اختر الجامعة --</option>
        @foreach ($universities as $univ)
            <option value="{{ $univ->id }}" {{ old('university_id', $academic_calendar->university_id ?? '') == $univ->id ? 'selected' : '' }}>
                {{ $univ->name }}
            </option>
        @endforeach
    </select>
    @error('university_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="year_label" class="form-label">العام الدراسي</label>
    <input type="text" name="year_label" class="form-control @error('year_label') is-invalid @enderror"
           value="{{ old('year_label', $academic_calendar->year_label ?? '') }}" required>
    @error('year_label') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="starts_on" class="form-label">تاريخ البداية</label>
        <input type="date" name="starts_on" class="form-control @error('starts_on') is-invalid @enderror"
               value="{{ old('starts_on', isset($academic_calendar) ? $academic_calendar->starts_on->format('Y-m-d') : '') }}" required>
        @error('starts_on') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="ends_on" class="form-label">تاريخ النهاية</label>
        <input type="date" name="ends_on" class="form-control @error('ends_on') is-invalid @enderror"
               value="{{ old('ends_on', isset($academic_calendar) ? $academic_calendar->ends_on->format('Y-m-d') : '') }}" required>
        @error('ends_on') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
        {{ old('is_active', $academic_calendar->is_active ?? 1) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">نشط؟</label>
</div>

<button type="submit" class="btn btn-success">حفظ</button>
