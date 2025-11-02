@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1 class="mb-3">إضافة فصل</h1>

  <div class="card">
    <div class="card-body">
      <form method="post" action="{{ route('admin.medical_terms.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.medical_terms.form', ['term'=>null])
        <button class="btn btn-primary mt-2">حفظ</button>
        <a href="{{ route('admin.medical_terms.index') }}" class="btn btn-light mt-2">رجوع</a>
      </form>
    </div>
  </div>
</div>
@endsection