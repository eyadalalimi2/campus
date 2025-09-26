@extends('admin.layouts.app')
@section('title','تعديل فيديو')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">تعديل فيديو</h1>
  <a href="{{ route('admin.med_videos.index') }}" class="btn btn-light"><i class="bi bi-arrow-right"></i> رجوع</a>
</div>

<form action="{{ route('admin.med_videos.update',$video) }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf @method('PUT')
  @include('admin.med_videos.form',['video'=>$video])
  <div class="mt-3"><button class="btn btn-primary"><i class="bi bi-save2"></i> تحديث</button></div>
</form>
@endsection
