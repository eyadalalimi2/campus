@csrf
<div class="mb-3">
  <label class="form-label">التخصص</label>
  <select name="major_id" class="form-select" required>
    <option value="">-- اختر --</option>
    @foreach($majors as $m)
      <option value="{{ $m->id }}" @selected(old('major_id', $item->major_id ?? null) == $m->id)>{{ $m->name }}</option>
    @endforeach
  </select>
</div>

<div class="mb-3">
  <label class="form-label">البرنامج</label>
  <select name="program_id" class="form-select" required>
    <option value="">-- اختر --</option>
    @foreach($programs as $p)
      <option value="{{ $p->id }}" @selected(old('program_id', $item->program_id ?? null) == $p->id)>{{ $p->name }}</option>
    @endforeach
  </select>
</div>

<button class="btn btn-success">حفظ</button>
