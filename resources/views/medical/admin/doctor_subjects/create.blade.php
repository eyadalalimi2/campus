
@extends('admin.layouts.app')
@section('title','ربط دكتور↔مادة (جديد)')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-link"></i> ربط دكتور↔مادة جديد</div>
      <div class="card-body">
        <form method="post" action="{{ route('medical.doctor-subjects.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">الدكتور</label>
            <select name="doctor_id" class="form-select" required>
              @foreach($doctors as $d)<option value="{{ $d->id }}">{{ $d->name }}</option>@endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">المادة</label>
            <select name="subject_id" class="form-select" required>
              @foreach($subjects as $s)<option value="{{ $s->id }}">{{ $s->code }} — {{ $s->name_ar }}</option>@endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">الأولوية (0=أعلى)</label>
            <input type="number" name="priority" value="5" min="0" max="9" class="form-control">
          </div>
          <div class="mb-3 form-check">
            <input type="checkbox" name="featured" class="form-check-input" id="featuredCheck">
            <label class="form-check-label" for="featuredCheck">مميز</label>
          </div>
          <button class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
