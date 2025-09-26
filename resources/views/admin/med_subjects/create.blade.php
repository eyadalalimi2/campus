@extends('admin.layouts.app')
@section('title','مادة جديدة')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">مادة جديدة</h1>
  <a href="{{ route('admin.med_subjects.index') }}" class="btn btn-light"><i class="bi bi-arrow-right"></i> رجوع</a>
</div>

<form action="{{ route('admin.med_subjects.store') }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf
  @include('admin.med_subjects.form',['subject'=>null,'selected'=>old('device_ids',[])])
  <div class="mt-3"><button class="btn btn-success"><i class="bi bi-check2-circle"></i> حفظ</button></div>
</form>
@endsection
