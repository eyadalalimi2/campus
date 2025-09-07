@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>تعديل برنامج</h1>
    <form action="{{ route('admin.programs.update',$program) }}" method="POST">
        @method('PUT')
        @include('admin.programs.form', ['program' => $program])
    </form>
</div>
@endsection
