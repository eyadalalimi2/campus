@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>تعديل مجال</h1>
    <form action="{{ route('admin.disciplines.update',$discipline) }}" method="POST">
        @method('PUT')
        @include('admin.disciplines.form', ['discipline' => $discipline])
    </form>
</div>
@endsection
