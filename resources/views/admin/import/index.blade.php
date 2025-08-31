@extends('admin.layouts.app')
@section('title','استيراد Excel')
@section('content')
<h4 class="mb-3">استيراد من Excel/CSV</h4>

<div class="alert alert-info">قم بتحميل أحد القوالب، ثم ارفع الملف بعد تعبئته.</div>
<div class="mb-3 d-flex gap-2">
  <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.import.sample','universities') }}">قالب جامعات</a>
  <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.import.sample','colleges') }}">قالب كليات</a>
  <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.import.sample','majors') }}">قالب تخصصات</a>
</div>

<form action="{{ route('admin.import.run') }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf
  <div class="row g-3">
    <div class="col-md-4">
      <label class="form-label">نوع البيانات</label>
      <select name="type" class="form-select" required>
        <option value="universities">جامعات</option>
        <option value="colleges">كليات</option>
        <option value="majors">تخصصات</option>
      </select>
    </div>
    <div class="col-md-6">
      <label class="form-label">ملف Excel/CSV</label>
      <input type="file" name="file" class="form-control" accept=".xlsx,.csv,.txt" required>
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button class="btn btn-primary w-100">استيراد</button>
    </div>
  </div>
</form>
@endsection
