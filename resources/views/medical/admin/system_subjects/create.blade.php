@extends('admin.layouts.app')
@section('title','ربط جديد')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-link"></i> ربط جهاز↔مادة جديد</div>
      <div class="card-body">
        <form method="post" action="{{ route('medical.system-subjects.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">الجهاز</label>
            <select name="system_id" class="form-select" required>
              @foreach($systems as $s)
                <option value="{{ $s->id }}">{{ $s->name_ar }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">المادة</label>
            <select name="subject_id" class="form-select" required>
              @foreach($subjects as $s)
                <option value="{{ $s->id }}">{{ $s->name_ar }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">الفصل</label>
            <input name="semester_hint" type="number" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">المستوى</label>
            <input name="level" type="number" class="form-control">
          </div>
          <button class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
