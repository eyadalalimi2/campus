@extends('admin.layouts.app')
@section('title','طلب #'.$requestItem->id)

@section('content')
<div class="d-flex align-items-center mb-3">
  <h1 class="h4 mb-0">طلب #{{ $requestItem->id }}</h1>
  <a href="{{ route('admin.requests.index') }}" class="btn btn-secondary ms-auto">عودة</a>
</div>

<div class="row g-3">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">تفاصيل الطلب</div>
      <div class="card-body">
        <div class="mb-2"><span class="text-muted">المستخدم:</span> {{ $requestItem->user?->name }} ({{ $requestItem->user?->email }})</div>
        <div class="mb-2"><span class="text-muted">النوع:</span> {{ $requestItem->type }}</div>
        <div class="mb-2"><span class="text-muted">الموضوع:</span> {{ $requestItem->subject }}</div>
        <div class="mb-2"><span class="text-muted">المحتوى:</span><br>{{ $requestItem->body }}</div>
        <div class="mb-2"><span class="text-muted">الحالة الحالية:</span> <span class="badge bg-primary">{{ $requestItem->status }}</span></div>
        @if($requestItem->admin_note)
          <div class="mb-2"><span class="text-muted">ملاحظة المسؤول:</span> {{ $requestItem->admin_note }}</div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card mb-3">
      <div class="card-header">تعيين لمسؤول</div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.requests.assign',$requestItem) }}">
          @csrf @method('PUT')
          <div class="mb-2">
            <label class="form-label">المسؤول</label>
            <select class="form-select" name="assigned_admin_id">
              <option value="">—</option>
              @foreach($admins as $ad)
                <option value="{{ $ad->id }}" @selected($requestItem->assigned_admin_id==$ad->id)>{{ $ad->name }}</option>
              @endforeach
            </select>
          </div>
          <button class="btn btn-outline-primary w-100">حفظ</button>
        </form>
      </div>
    </div>

    <div class="card">
      <div class="card-header">تغيير الحالة</div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.requests.status',$requestItem) }}">
          @csrf @method('PUT')
          <div class="mb-2">
            <label class="form-label">الحالة</label>
            <select class="form-select" name="status" required>
              <option value="open" @selected($requestItem->status==='open')>مفتوح</option>
              <option value="in_progress" @selected($requestItem->status==='in_progress')>قيد المعالجة</option>
              <option value="closed" @selected($requestItem->status==='closed')>مغلق</option>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label">ملاحظة</label>
            <textarea class="form-control" name="admin_note" rows="3">{{ old('admin_note',$requestItem->admin_note) }}</textarea>
          </div>
          <button class="btn btn-outline-success w-100">تحديث الحالة</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
