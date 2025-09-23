
@extends('admin.layouts.app')
@section('title','تعديل مادة')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-journal-bookmark"></i> تعديل مادة</div>
      <div class="card-body">
        <form method="post" action="{{ route('medical.subjects.update',$subject) }}">
          @csrf @method('PUT')
          <div class="mb-3">
            <label class="form-label">الكود</label>
            <input name="code" class="form-control" value="{{ $subject->code }}" required maxlength="50">
          </div>
          <div class="mb-3">
            <label class="form-label">الاسم (عربي)</label>
            <input name="name_ar" class="form-control" value="{{ $subject->name_ar }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">الاسم (إنجليزي)</label>
            <input name="name_en" class="form-control" value="{{ $subject->name_en }}">
          </div>
          <div class="mb-3">
            <label class="form-label">النطاق</label>
            <select name="track_scope" class="form-select">
              @foreach(['BASIC','CLINICAL','BOTH'] as $opt)
                <option value="{{ $opt }}" {{ $subject->track_scope===$opt?'selected':'' }}>{{ $opt }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-check mb-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ $subject->is_active?'checked':'' }}>
            <label class="form-check-label" for="is_active">فعال</label>
          </div>
          <button class="btn btn-success"><i class="bi bi-save"></i> تحديث</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
