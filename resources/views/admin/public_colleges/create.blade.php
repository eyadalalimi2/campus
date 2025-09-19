@extends('admin.layouts.app')


@section('content')
  <h1 class="mb-3">إضافة كلية عامة</h1>

  @if($errors->any())
    <div class="alert alert-danger">
      <div>تحقق من الحقول التالية:</div>
      <ul class="mb-0">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <form method="post" action="{{ route('admin.public-colleges.store') }}" class="card card-body">
    @csrf

    @include('admin.public_colleges.form', ['item' => (object)[]])

    <div class="mt-3 d-flex gap-2">
      <button type="submit" class="btn btn-success">حفظ</button>
      <a href="{{ route('admin.public-colleges.index') }}" class="btn btn-outline-secondary">إلغاء</a>
    </div>
  </form>
@endsection
