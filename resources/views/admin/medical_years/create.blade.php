@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1 class="mb-3">إضافة سنة</h1>

  <div class="card">
    <div class="card-body">
      <form method="post" action="{{ route('admin.medical_years.store') }}">
        @csrf
        @include('admin.medical_years.form', ['year' => null])
        <button class="btn btn-primary mt-2">حفظ</button>
        <a href="{{ route('admin.medical_years.index') }}" class="btn btn-light mt-2">رجوع</a>
      </form>
    </div>
  </div>
</div>
@endsection