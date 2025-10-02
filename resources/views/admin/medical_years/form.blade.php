<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">التخصص (Major)</label>
    <select name="major_id" class="form-select">
      @foreach($majors as $id => $name)
        <option value="{{ $id }}" @selected(old('major_id', optional($year)->major_id) == $id)>{{ $name }}</option>
      @endforeach
    </select>
    @error('major_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">رقم السنة (1..6)</label>
    <input type="number" min="1" max="6" name="year_number" class="form-control" value="{{ old('year_number', optional($year)->year_number) }}">
    @error('year_number')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">الترتيب</label>
    <input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', optional($year)->sort_order) }}">
  </div>

  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active_year" @checked(old('is_active', optional($year)->is_active))>
      <label class="form-check-label" for="is_active_year">مفعل</label>
    </div>
  </div>
</div>