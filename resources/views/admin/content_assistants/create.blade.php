@extends('admin.layouts.app')

@section('title', 'إضافة مساعد محتوى')

@section('content')
<div class="container">
    <h3 class="mb-3">إضافة مساعد محتوى</h3>
    @include('admin.content_assistants.form', [
        'action' => route('admin.content_assistants.store'),
        'method' => 'POST',
        'assistant' => null
    ])
</div>
@endsection
