@extends('admin.layouts.app')
@section('title','إضافة ميزة')
@section('content')
<h4 class="mb-3">إضافة ميزة — الخطة: {{ $plan->name }}</h4>
<form action="{{ route('admin.plan_features.store', ['plan'=>$plan->id]) }}" method="POST" class="card p-3">
  @include('admin.plan_features.form', ['feature' => $feature])
  <div class="mt-3">
    <a href="{{ route('admin.plan_features.index', ['plan'=>$plan->id]) }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
