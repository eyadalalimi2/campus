@extends('admin.layouts.app')

@section('title', 'تعديل مساعد محتوى')

@section('content')
<div class="container">
    <h3 class="mb-3">تعديل مساعد محتوى</h3>
    @include('admin.content_assistants.form', [
        'action' => route('admin.content_assistants.update', $assistant),
        'method' => 'PUT',
        'assistant' => $assistant
    ])
</div>
@endsection
