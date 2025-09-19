@extends('admin.layouts.app')

@section('content')
  <h1 class="mb-3">تعديل تخصص عام</h1>

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <form method="post" action="{{ route('admin.public-majors.update', $item) }}" class="card card-body">
    @csrf
    @method('PUT')
    @include('admin.public_majors.form', ['item' => $item, 'colleges' => $colleges])

    <div class="mt-3 d-flex gap-2">
      <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
      <a href="{{ route('admin.public-majors.index') }}" class="btn btn-outline-secondary">رجوع</a>
    </div>
  </form>
@endsection
