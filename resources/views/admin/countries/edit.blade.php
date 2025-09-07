@extends('admin.layouts.app')

@section('title', 'تعديل دولة')

@section('content')
<div class="container">
    <h4 class="mb-3">تعديل دولة</h4>
    <form action="{{ route('admin.countries.update', $country) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.countries.form', ['country' => $country])
    </form>
</div>
@endsection
