@csrf
<div class="mb-3">
    <label for="calendar_id" class="form-label">التقويم الأكاديمي</label>
    <select name="calendar_id" id="calendar_id" class="form-select @error('calendar_id') is-invalid @enderror">
        <option value="">-- اختر التقويم --</option>
        @foreach ($calendars as $cal)
            <option value="{{ $cal->id }}" {{ old('calendar_id', $academic_term->calendar_id ?? '') == $cal->id ? 'selected' : '' }}>
                {{ $cal->year_label }} - {{ $cal->university->name }}
            </option>
        @endforeach
    </select>
    @error('calendar_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="name" class="form-label">الفصل</label>
    <select name="name" id="name" class="form-select @error('name') is-invalid @enderror">
        @foreach(['first' => 'الأول','second' => 'الثاني','summer' => 'الصيفي'] as $val => $label)
            <option value="{{ $val }}" {{ old('name', $academic_term->name ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <label for="starts_on" class="form-label">تاريخ البداية</label>
        <input type="date" name="starts_on" class="form-control @error('starts_on') is-invalid @enderror"
               value="{{ old('starts_on', isset($academic_term) ? $academic_term->starts_on->format('Y-m-d') : '') }}" required>
        @error('starts_on') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label for="ends_on" class="form-label">تاريخ النهاية</label>
        <input type="date" name="ends_on" class="form-control @error('ends_on') is-invalid @enderror"
               value="{{ old('ends_on', isset($academic_term) ? $academic_term->ends_on->format('Y-m-d') : '') }}" required>
        @error('ends_on') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
        {{ old('is_active', $academic_term->is_active ?? 1) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">نشط؟</label>
</div>

<button type="submit" class="btn btn-success">حفظ</button>
