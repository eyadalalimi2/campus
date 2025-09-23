
@extends('admin.layouts.app')
@section('title','تعديل ربط دكتور↔مادة↔جهاز')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-7 col-lg-6">
    <div class="card">
      <div class="card-header"><i class="bi bi-link"></i> تعديل ربط دكتور↔مادة↔جهاز</div>
      <div class="card-body">
        <form method="post" action="{{ route('medical.doctor-subject-systems.update',$item) }}">
          @csrf @method('PUT')
          <div class="mb-3">
            <label class="form-label">دكتور↔مادة</label>
            <select name="doctor_subject_id" class="form-select" required>
              @foreach($doctorSubjects as $ds)
                <option value="{{ $ds->id }}" {{ $item->doctor_subject_id==$ds->id?'selected':'' }}>
                  {{ $ds->doctor->name }} — {{ $ds->subject->code }} ({{ $ds->subject->name_ar }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">الجهاز</label>
            <select name="system_id" class="form-select" required>
              @foreach($systems as $s)
                <option value="{{ $s->id }}" {{ $item->system_id==$s->id?'selected':'' }}>{{ $s->name_ar }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Playlist ID</label>
            <input name="playlist_id" class="form-control" value="{{ $item->playlist_id }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Tag</label>
            <input name="tag" class="form-control" value="{{ $item->tag }}">
          </div>
          <button class="btn btn-success"><i class="bi bi-save"></i> تحديث</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
