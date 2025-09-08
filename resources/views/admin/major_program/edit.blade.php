@extends('admin.layouts.app')

@section('content')
<h1 class="mb-4">تعديل الربط</h1>
<form method="POST" action="{{ route('admin.major_program.update', $item) }}">
  @method('PUT')
  @include('admin.major_program.form')
</form>
@endsection
