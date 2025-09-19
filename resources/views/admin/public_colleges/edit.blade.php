@extends('admin.layouts.app')

@section('content')
  <h1 class="mb-3">تعديل كلية عامة</h1>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <form method="post" action="{{ route('admin.public-colleges.update', $item) }}" class="card card-body">
    @csrf
    @method('PUT')

    @include('admin.public_colleges.form', ['item' => $item])

    <div class="mt-3 d-flex gap-2">
      <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
      <a href="{{ route('admin.public-colleges.index') }}" class="btn btn-outline-secondary">رجوع</a>
    </div>
  </form>
@endsection
