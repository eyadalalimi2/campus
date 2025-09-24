
@extends('admin.layouts.app')
@section('title','تعديل ربط دكتور↔مادة')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-link"></i> تعديل ربط دكتور↔مادة</div>
      <div class="card-body">
        <form method="post" action="{{ route('medical.doctor-subjects.update',$item) }}">
          @csrf @method('PUT')
          <div class="mb-3">
            <label class="form-label">الدكتور</label>
            <select name="doctor_id" class="form-select" required>
              @foreach($doctors as $d)
                <option value="{{ $d->id }}" {{ $item->doctor_id==$d->id?'selected':'' }}>{{ $d->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">المادة</label>
            <select name="subject_id" class="form-select" required>
              @foreach($subjects as $s)
                <option value="{{ $s->id }}" {{ $item->subject_id==$s->id?'selected':'' }}>{{ $s->code }} — {{ $s->name_ar }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">الأولوية</label>
            <input type="number" name="priority" class="form-control" value="{{ $item->priority }}" min="0" max="9">
          </div>
          <div class="mb-3 form-check">
            <input type="hidden" name="featured" value="0">
            <input type="checkbox" name="featured" value="1" class="form-check-input" id="featuredCheck" {{ $item->featured?'checked':'' }}>
            <label class="form-check-label" for="featuredCheck">مميز</label>
          </div>
          <button class="btn btn-success"><i class="bi bi-save"></i> تحديث</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
