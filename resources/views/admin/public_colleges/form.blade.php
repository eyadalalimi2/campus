{{-- الموارد المشتركة لنموذج الكليات العامة --}}
<div class="mb-3">
  <label class="form-label">اسم الكلية العامة</label>
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
