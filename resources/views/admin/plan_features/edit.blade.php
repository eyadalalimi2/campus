@extends('admin.layouts.app')
@section('title','تعديل ميزة')
@section('content')
<h4 class="mb-3">تعديل ميزة — الخطة: {{ $plan->name }}</h4>
<form action="{{ route('admin.plan_features.update', ['plan'=>$plan->id, 'feature'=>$feature->id]) }}"
      method="POST" class="card p-3">
  @method('PUT')
  @include('admin.plan_features.form', ['feature' => $feature])
  <div class="mt-3">
    <a href="{{ route('admin.plan_features.index', ['plan'=>$plan->id]) }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
