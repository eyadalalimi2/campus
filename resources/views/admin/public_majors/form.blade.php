{{-- الموارد المشتركة لنموذج التخصصات العامة --}}
<div class="mb-3">
  <label class="form-label">الكلية العامة</label>
  <select name="public_college_id" class="form-select" required>
    <option value="">— اختر الكلية العامة —</option>
    @foreach(($colleges ?? []) as $c)
      <option value="{{ $c->id }}"
        @selected( (int)old('public_college_id', $item->public_college_id ?? 0) === (int)$c->id )>
        {{ $c->name }}
      </option>
    @endforeach
  </select>
  @error('public_college_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
  <label class="form-label">اسم التخصص العام</label>
  <input type="text" name="name" class="form-control"
         value="{{ old('name', $item->name ?? '') }}" maxlength="190" required>
  @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>


<div class="mb-3">
  <label class="form-label">الحالة</label>
  @php $status = old('status', $item->status ?? 'active'); @endphp
  <select name="status" class="form-select" required>
    <option value="active"   @selected($status === 'active')>نشط</option>
    <option value="archived" @selected($status === 'archived')>مؤرشف</option>
  </select>
  @error('status')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>
