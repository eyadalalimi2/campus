@extends('admin.layouts.app')

@section('title', 'إضافة دولة')

@section('content')
<div class="container">
    <h4 class="mb-3">إضافة دولة</h4>
    <form action="{{ route('admin.countries.store') }}" method="POST">
        @include('admin.countries.form')
    </form>
</div>
@endsection
