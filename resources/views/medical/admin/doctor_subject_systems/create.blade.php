
@extends('admin.layouts.app')
@section('title','ربط دكتور↔مادة↔جهاز (جديد)')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-link"></i> ربط دكتور↔مادة↔جهاز جديد</div>
      <div class="card-body">
        <form method="post" action="{{ route('medical.doctor-subject-systems.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">دكتور↔مادة</label>
            <select name="doctor_subject_id" class="form-select" required>
              @foreach($doctorSubjects as $ds)
                <option value="{{ $ds->id }}">
                  {{ $ds->doctor->name }} — {{ $ds->subject->code }} ({{ $ds->subject->name_ar }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">الجهاز</label>
            <select name="system_id" class="form-select" required>
              @foreach($systems as $s)<option value="{{ $s->id }}">{{ $s->name_ar }}</option>@endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Playlist ID</label>
            <input name="playlist_id" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Tag</label>
            <input name="tag" class="form-control" placeholder="system:CARDIO">
          </div>
          <button class="btn btn-primary"><i class="bi bi-save"></i> حفظ</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
