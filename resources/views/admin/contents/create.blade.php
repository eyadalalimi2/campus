@extends('admin.layouts.app')
@section('title','إضافة محتوى')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">إضافة محتوى</h4>
  <a href="{{ route('admin.contents.index') }}" class="btn btn-outline-secondary">
    <i class="bi bi-arrow-right-circle"></i> رجوع
  </a>
</div>

<div class="alert alert-warning d-flex align-items-start gap-2">
  <i class="bi bi-shield-lock fs-5"></i>
  <div>
    هذا المحتوى <strong>خاص بالجامعة</strong>: يجب اختيار <strong>جامعة</strong>، ويمكن (اختياريًا) تقييده بـ
    <strong>فرع/كلية/تخصص/مادة</strong>.
    عند اختيار حالة <strong>منشور</strong> سيتم تعيين الناشر وتاريخ النشر تلقائيًا.
  </div>
</div>

<form action="{{ route('admin.contents.store') }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf

  @include('admin.contents.form', ['content'=>null])

  <div class="mt-3">
    <label class="form-label">مذكرة الإصدار (اختياري)</label>
    <textarea name="changelog" rows="3" class="form-control"
      placeholder="أكتب ملاحظات مختصرة عمّا يحتويه هذا الإصدار (للمراجعة الداخلية)">{{ old('changelog') }}</textarea>
    <div class="form-text">
      * الإصدار يبدأ بـ <code>v1</code> ويزداد تلقائياً عند تغيير الملف أو الرابط في الإصدارات اللاحقة.
    </div>
  </div>

  <div class="mt-3 d-flex gap-2">
    <button class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
    <a href="{{ route('admin.contents.index') }}" class="btn btn-link">إلغاء</a>
  </div>
</form>
@endsection
