@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>تعديل تقويم أكاديمي</h1>
    <form action="{{ route('admin.academic-calendars.update', $academic_calendar) }}" method="POST">
        @method('PUT')
        @include('admin.academic_calendars.form', ['academic_calendar' => $academic_calendar])
    </form>
</div>
@endsection
