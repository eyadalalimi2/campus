@extends('admin.layouts.app')
@section('title','تعديل خطة')
@section('content')
<h4 class="mb-3">تعديل خطة: {{ $plan->name }}</h4>
<form action="{{ route('admin.plans.update', $plan) }}" method="POST" class="card p-3">
  @csrf @method('PUT')
  @include('admin.plans.form', ['plan' => $plan])
  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="{{ route('admin.plans.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
