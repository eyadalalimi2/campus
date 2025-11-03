@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1 class="mb-3">إضافة نظام</h1>

  <div class="card">
    <div class="card-body">
  <form method="post" action="{{ route('admin.medical_systems.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.medical_systems.form', ['system'=>null])
        <button class="btn btn-primary mt-2">حفظ</button>
        <a href="{{ route('admin.medical_systems.index') }}" class="btn btn-light mt-2">رجوع</a>
      </form>
    </div>
  </div>
</div>
@endsection