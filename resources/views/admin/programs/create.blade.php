@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>إضافة برنامج</h1>
    <form action="{{ route('admin.programs.store') }}" method="POST">
        @include('admin.programs.form')
    </form>
</div>
@endsection
