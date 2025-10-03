<div class="row g-3">

  <div class="col-lg-6">
    <label class="form-label">السنة</label>
    <select name="year_id" class="form-select">
      @foreach($years as $id => $label)
        <option value="{{ $id }}" @selected(old('year_id', optional($system)->year_id) == $id)>{{ $label }}</option>
      @endforeach
    </select>
    @error('year_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="col-lg-6">
    <label class="form-label">الترم</label>
    <select name="term_id" id="term_id_select" class="form-select">
      <!-- سيتم تعبئتها بالجافاسكريبت -->
    </select>
    @error('term_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <script>
    const termsByYear = @json($termsByYear);
    const oldYear = '{{ old('year_id', optional($system)->year_id) }}';
    const oldTerm = '{{ old('term_id', optional($system)->term_id) }}';
    function updateTerms() {
      const yearId = document.querySelector('[name=year_id]').value;
      const termSelect = document.getElementById('term_id_select');
      termSelect.innerHTML = '';
      if (termsByYear[yearId]) {
        termsByYear[yearId].forEach(function(term) {
          const opt = document.createElement('option');
          opt.value = term.id;
          opt.textContent = 'ترم ' + term.number;
          if (String(term.id) === oldTerm) opt.selected = true;
          termSelect.appendChild(opt);
        });
      }
    }
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelector('[name=year_id]').addEventListener('change', updateTerms);
      updateTerms();
    });
  </script>

  <div class="col-lg-6">
    <label class="form-label">الجهاز العام (med_devices)</label>
    <select name="med_device_id" class="form-select">
      @foreach($devices as $id => $name)
        <option value="{{ $id }}" @selected(old('med_device_id', optional($system)->med_device_id) == $id)>{{ $name }}</option>
      @endforeach
    </select>
    @error('med_device_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-8">
    <label class="form-label">الاسم الظاهر</label>
    <input type="text" name="display_name" class="form-control" value="{{ old('display_name', optional($system)->display_name) }}">
  </div>

  <div class="col-md-4">
    <label class="form-label">الترتيب</label>
    <input type="number" min="0" name="sort_order" class="form-control" value="{{ old('sort_order', optional($system)->sort_order) }}">
  </div>

  <div class="col-12">
    <label class="form-label">ملاحظات</label>
    <textarea name="notes" class="form-control" rows="3">{{ old('notes', optional($system)->notes) }}</textarea>
  </div>

  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active_system" @checked(old('is_active', optional($system)->is_active))>
      <label class="form-check-label" for="is_active_system">مفعل</label>
    </div>
  </div>
</div>