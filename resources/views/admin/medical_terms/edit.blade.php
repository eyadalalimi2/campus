@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1 class="mb-3">تعديل فصل #{{ $term->id }}</h1>

  <div class="card">
    <div class="card-body">
      <form method="post" action="{{ route('admin.medical_terms.update',$term) }}">
        @csrf @method('PUT')
        @include('admin.medical_terms.form', ['term'=>$term])
        <button class="btn btn-primary mt-2">تحديث</button>
        <a href="{{ route('admin.medical_terms.index') }}" class="btn btn-light mt-2">رجوع</a>
      </form>
    </div>
  </div>
</div>
@endsection