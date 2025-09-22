@php
  // في حال لم تُمرر من الكنترولر
  $universities = $universities ?? \App\Models\University::orderBy('name')->get();

  $selUni = old('university_id', $branch->university_id ?? '');
  $name   = old('name',         $branch->name ?? '');
  $addr   = old('address',      $branch->address ?? '');
  $phone  = old('phone',        $branch->phone ?? '');
  $email  = old('email',        $branch->email ?? '');
  $active = old('is_active',    $branch->is_active ?? true);
@endphp

<div class="row g-3">
  {{-- الجامعة --}}
  <div class="col-md-4">
    <label class="form-label">الجامعة <span class="text-danger">*</span></label>
    <select name="university_id" class="form-select" required>
      <option value="">— اختر —</option>
      @foreach($universities as $u)
        <option value="{{ $u->id }}" @selected((string)$selUni === (string)$u->id)>{{ $u->name }}</option>
      @endforeach
    </select>
  </div>

  {{-- الاسم --}}
  <div class="col-md-4">
    <label class="form-label">اسم الفرع <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" required value="{{ $name }}" maxlength="255">
  </div>

  {{-- الحالة --}}
  <div class="col-md-4 d-flex align-items-end">
    <div class="form-check">
      <input type="hidden" name="is_active" value="0">
      <input class="form-check-input" type="checkbox" id="b_active" name="is_active" value="1" {{ $active ? 'checked' : '' }}>
      <label class="form-check-label" for="b_active">مفعل</label>
    </div>
  </div>

  {{-- العنوان --}}
  <div class="col-12">
    <label class="form-label">العنوان</label>
    <input type="text" name="address" class="form-control" value="{{ $addr }}" maxlength="255" placeholder="العنوان التفصيلي (اختياري)">
  </div>

  {{-- هاتف + بريد --}}
  <div class="col-md-6">
    <label class="form-label">رقم الهاتف</label>
    <input type="text" name="phone" class="form-control" value="{{ $phone }}" maxlength="30">
  </div>
  <div class="col-md-6">
    <label class="form-label">البريد الإلكتروني</label>
    <input type="email" name="email" class="form-control" value="{{ $email }}" maxlength="255">
  </div>
</div>
