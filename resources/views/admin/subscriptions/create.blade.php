@extends('admin.layouts.app')
@section('title','اشتراك جديد')
@section('content')
<h4 class="mb-3">اشتراك جديد</h4>
<form action="{{ route('admin.subscriptions.store') }}" method="POST" class="card p-3">
  @csrf
  @include('admin.subscriptions.form', ['subscription'=>null])
  <div class="mt-3">
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
