@extends('admin.layouts.app')

@section('content')
<h1 class="mb-4">إضافة ربط تخصص ← برنامج</h1>
<form method="POST" action="{{ route('admin.major_program.store') }}">
  @include('admin.major_program.form')
</form>
@endsection
