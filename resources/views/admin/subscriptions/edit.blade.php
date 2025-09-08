@extends('admin.layouts.app')
@section('title','تعديل اشتراك')

@section('content')
<h4 class="mb-3">تعديل اشتراك الطالب: {{ $subscription->user?->name }}</h4>

<form action="{{ route('admin.subscriptions.update', $subscription) }}" method="POST" class="card p-3">
  @csrf @method('PUT')

  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">الخطة</label>
      <input type="text" class="form-control" value="{{ $subscription->plan?->name ?? '—' }}" disabled>
      <div class="form-text">الخطة تُحدّد بواسطة كود التفعيل ولا يمكن تغييرها يدوياً.</div>
    </div>

    <div class="col-md-4">
      <label class="form-label">الكود</label>
      <input type="text" class="form-control" value="{{ $subscription->activationCode?->code ?? '—' }}" disabled>
    </div>

    <div class="col-md-4">
      <label class="form-label">الحالة</label>
      @php $st = old('status', $subscription->status); @endphp
      <select name="status" class="form-select" required>
        <option value="active"    @selected($st==='active')>نشط</option>
        <option value="expired"   @selected($st==='expired')>منتهي</option>
        <option value="cancelled" @selected($st==='cancelled')>ملغى</option>
        <option value="pending"   @selected($st==='pending')>قيد التفعيل</option>
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">تاريخ البداية</label>
      <input type="datetime-local" name="started_at" class="form-control"
             value="{{ old('started_at', optional($subscription->started_at)->format('Y-m-d\TH:i')) }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">تاريخ النهاية</label>
      <input type="datetime-local" name="ends_at" class="form-control"
             value="{{ old('ends_at', optional($subscription->ends_at)->format('Y-m-d\TH:i')) }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">التجديد التلقائي</label>
      <div class="form-control bg-light">غير متاح (نظام الأكواد)</div>
      <input type="hidden" name="auto_renew" value="0">
    </div>
  </div>

  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
