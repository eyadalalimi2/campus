@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>إضافة مجال</h1>
    <form action="{{ route('admin.disciplines.store') }}" method="POST">
        @include('admin.disciplines.form')
    </form>
</div>
@endsection
