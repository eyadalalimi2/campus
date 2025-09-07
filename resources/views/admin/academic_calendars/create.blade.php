@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>إضافة تقويم أكاديمي</h1>
    <form action="{{ route('admin.academic-calendars.store') }}" method="POST">
        @include('admin.academic_calendars.form')
    </form>
</div>
@endsection
