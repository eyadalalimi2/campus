@extends('admin.layouts.app')
@section('title','إرسال إشعار')

@section('content')
<h1 class="h4 mb-3">إرسال إشعار</h1>

<form method="POST" action="{{ route('admin.notifications.store') }}" class="card card-body">
  @csrf
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label required">العنوان</label>
      <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">الرابط (اختياري)</label>
      <input type="url" class="form-control" name="action_url" value="{{ old('action_url') }}" placeholder="رابط فتح عند النقر">
    </div>
    <div class="col-12">
      <label class="form-label required">النص</label>
      <textarea class="form-control" rows="4" name="body" required>{{ old('body') }}</textarea>
    </div>

    <div class="col-md-3">
      <label class="form-label required">نوع الهدف</label>
      <select name="target_type" class="form-select" required>
        <option value="all" @selected(old('target_type')==='all')>لكل المستخدمين</option>
        <option value="user" @selected(old('target_type')==='user')>مستخدم محدد</option>
        <option value="major" @selected(old('target_type')==='major')>تخصص</option>
        <option value="university" @selected(old('target_type')==='university')>جامعة</option>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">المعرف (إن وُجد)</label>
      <input type="number" class="form-control" name="target_id" value="{{ old('target_id') }}" placeholder="User/Major/University ID">
    </div>
    <div class="col-md-3">
      <label class="form-label">إرسال فوري؟</label>
      <select name="dispatch_now" class="form-select">
        <option value="1" @selected(old('dispatch_now','1')==='1')>نعم</option>
        <option value="0" @selected(old('dispatch_now')==='0')>لاحقاً</option>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">مرفق صورة (اختياري)</label>
      <input type="url" class="form-control" name="image_url" value="{{ old('image_url') }}" placeholder="رابط صورة">
    </div>
  </div>

  <div class="mt-3">
    <button class="btn btn-primary">إرسال</button>
    <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">إلغاء</a>
  </div>
</form>
@endsection
