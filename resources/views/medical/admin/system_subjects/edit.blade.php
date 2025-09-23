@extends('admin.layouts.app')
@section('title','تعديل ربط جهاز ↔ مادة')
@section('content')
<h1>تعديل ربط</h1>
<form method="post" action="{{ route('medical.system-subjects.update',$item) }}">
@csrf @method('PUT')
<label>الجهاز</label>
<select name="system_id" required>
  @foreach($systems as $s)
    <option value="{{ $s->id }}" {{ $item->system_id==$s->id?'selected':'' }}>{{ $s->name_ar }}</option>
  @endforeach
</select>
<label>المادة</label>
<select name="subject_id" required>
  @foreach($subjects as $subj)
    <option value="{{ $subj->id }}" {{ $item->subject_id==$subj->id?'selected':'' }}>
      {{ $subj->code }} — {{ $subj->name_ar }}
    </option>
  @endforeach
</select>
<label>Semester</label><input type="number" name="semester_hint" value="{{ $item->semester_hint }}" min="0" max="20">
<label>Level</label><input type="number" name="level" value="{{ $item->level }}" min="0" max="20">
<button>تحديث</button>
</form>
@endsection
