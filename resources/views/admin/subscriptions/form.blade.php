@php $isEdit = isset($subscription); @endphp
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">الطالب</label>
    <select name="user_id" class="form-select" required>
      <option value="">— اختر —</option>
      @foreach(\App\Models\User::orderBy('name')->get() as $u)
        <option value="{{ $u->id }}" @selected(old('user_id',$subscription->user_id ?? '')==$u->id)>{{ $u->name }} ({{ $u->student_number ?? '—' }})</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">الخطة</label>
    <input type="text" name="plan" class="form-control" required value="{{ old('plan',$subscription->plan ?? 'standard') }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">الحالة</label>
    @php $st = old('status',$subscription->status ?? 'active'); @endphp
    <select name="status" class="form-select">
      <option value="active" @selected($st==='active')>نشط</option>
      <option value="expired" @selected($st==='expired')>منتهي</option>
      <option value="canceled" @selected($st==='canceled')>ملغي</option>
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">بداية الاشتراك</label>
    <input type="datetime-local" name="started_at" class="form-control"
      value="{{ old('started_at', isset($subscription->started_at) ? $subscription->started_at->format('Y-m-d\TH:i') : '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">نهاية الاشتراك</label>
    <input type="datetime-local" name="ends_at" class="form-control"
      value="{{ old('ends_at', isset($subscription->ends_at) ? $subscription->ends_at->format('Y-m-d\TH:i') : '') }}">
  </div>
  <div class="col-md-4 d-flex align-items-end">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="auto_renew" value="1" id="auto_renew"
             {{ old('auto_renew',$subscription->auto_renew ?? false) ? 'checked':'' }}>
      <label class="form-check-label" for="auto_renew">تجديد تلقائي</label>
    </div>
  </div>

  <div class="col-md-6">
    <label class="form-label">السعر (بالـ YER)</label>
    <input type="number" name="price_cents" class="form-control" min="0"
           value="{{ old('price_cents',$subscription->price_cents ?? '') }}" placeholder="مثال: 5000">
  </div>
  <div class="col-md-6">
    <label class="form-label">العملة</label>
    <input type="text" name="currency" class="form-control" value="{{ old('currency',$subscription->currency ?? 'YER') }}" maxlength="3">
  </div>
</div>
