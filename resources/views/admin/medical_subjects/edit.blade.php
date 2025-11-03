@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1 class="mb-3">تعديل مادة #{{ $subject->id }}</h1>

  <div class="card">
    <div class="card-body">
  <form method="post" action="{{ route('admin.medical_subjects.update',$subject) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.medical_subjects.form', ['subject'=>$subject])
        <button class="btn btn-primary mt-2">تحديث</button>
        <a href="{{ route('admin.medical_subjects.index') }}" class="btn btn-light mt-2">رجوع</a>
      </form>
    </div>
  </div>
</div>
@endsection