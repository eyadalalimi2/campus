@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>تعديل فصل أكاديمي</h1>
    <form action="{{ route('admin.academic_terms.update',$academic_term) }}" method="POST">
        @method('PUT')
        @include('admin.academic_terms.form', ['academic_term' => $academic_term])
    </form>
</div>
@endsection
