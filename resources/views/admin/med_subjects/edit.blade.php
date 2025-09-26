@extends('admin.layouts.app')
@section('title','تعديل مادة')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">تعديل مادة</h1>
  <a href="{{ route('admin.med_subjects.index') }}" class="btn btn-light"><i class="bi bi-arrow-right"></i> رجوع</a>
</div>

<form action="{{ route('admin.med_subjects.update',$subject) }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf @method('PUT')
  @include('admin.med_subjects.form',['subject'=>$subject,'selected'=>old('device_ids',$selected ?? [])])
  <div class="mt-3"><button class="btn btn-primary"><i class="bi bi-save2"></i> تحديث</button></div>
</form>
@endsection
