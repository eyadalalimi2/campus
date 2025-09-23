
@extends('admin.layouts.app')
@section('title','مادة جديدة')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-journal-bookmark"></i> مادة جديدة</div>
      <div class="card-body">
        <form method="post" action="{{ route('medical.subjects.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">الكود</label>
            <input name="code" class="form-control" required maxlength="50">
          </div>
          <div class="mb-3">
            <label class="form-label">الاسم (عربي)</label>
            <input name="name_ar" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">الاسم (إنجليزي)</label>
            <input name="name_en" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">النطاق</label>
            <select name="track_scope" class="form-select" required>
               <option value="CLINICAL">CLINICAL</option>
               <option value="BASIC">BASIC</option>
               <option value="BOTH">BOTH</option>
            </select>
          </div>
          <div class="form-check mb-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" checked>
            <label class="form-check-label" for="is_active">فعال</label>
          </div>
          <button class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
