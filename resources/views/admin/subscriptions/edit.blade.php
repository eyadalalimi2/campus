@extends('admin.layouts.app')
@section('title','تعديل اشتراك')
@section('content')
<h4 class="mb-3">تعديل اشتراك</h4>
<form action="{{ route('admin.subscriptions.update',$subscription) }}" method="POST" class="card p-3">
  @csrf @method('PUT')
  @include('admin.subscriptions.form', ['subscription'=>$subscription])
  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
