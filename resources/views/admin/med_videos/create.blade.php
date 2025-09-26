@extends('admin.layouts.app')
@section('title','فيديو جديد')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">فيديو جديد</h1>
  <a href="{{ route('admin.med_videos.index') }}" class="btn btn-light"><i class="bi bi-arrow-right"></i> رجوع</a>
</div>

<form action="{{ route('admin.med_videos.store') }}" method="POST" enctype="multipart/form-data" class="card p-3">
  @csrf
  @include('admin.med_videos.form',['video'=>null])
  <div class="mt-3"><button class="btn btn-success"><i class="bi bi-check2-circle"></i> حفظ</button></div>
</form>
@endsection
