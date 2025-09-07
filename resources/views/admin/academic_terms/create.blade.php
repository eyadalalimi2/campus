@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>إضافة فصل أكاديمي</h1>
    <form action="{{ route('admin.academic-terms.store') }}" method="POST">
        @include('admin.academic_terms.form')
    </form>
</div>
@endsection
