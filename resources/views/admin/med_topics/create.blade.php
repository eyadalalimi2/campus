@extends('admin.layouts.app')
@section('title','موضوع جديد')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 m-0">موضوع جديد</h1>
  <a href="{{ route('admin.med_topics.index') }}" class="btn btn-light"><i class="bi bi-arrow-right"></i> رجوع</a>
</div>

<form action="{{ route('admin.med_topics.store') }}" method="POST" class="card p-3">
  @csrf
  @include('admin.med_topics.form',['topic'=>null])
  <div class="mt-3"><button class="btn btn-success"><i class="bi bi-check2-circle"></i> حفظ</button></div>
</form>
@endsection
