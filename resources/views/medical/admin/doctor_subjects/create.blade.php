
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
            <input type="hidden" name="featured" value="0">
            <input type="checkbox" name="featured" value="1" class="form-check-input" id="featuredCheck">
            <label class="form-check-label" for="featuredCheck">مميز</label>
          </div>
          <button class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
        </form>
        @if ($errors->any())
          <div class="alert alert-danger mt-3">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ str_replace([
                  'The featured field must be true or false.',
                  'The doctor id field is required.',
                  'The subject id field is required.',
                  'The priority field must be an integer.'
                ], [
                  'حقل التمييز يجب أن يكون نعم أو لا.',
                  'حقل الدكتور مطلوب.',
                  'حقل المادة مطلوب.',
                  'حقل الأولوية يجب أن يكون رقمًا صحيحًا.'
                ], $error) }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
