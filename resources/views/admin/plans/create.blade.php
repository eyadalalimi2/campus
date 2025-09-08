@extends('admin.layouts.app')
@section('title','إضافة خطة')
@section('content')
<h4 class="mb-3">إضافة خطة</h4>
<form action="{{ route('admin.plans.store') }}" method="POST" class="card p-3">
  @csrf
  @include('admin.plans.form', ['plan' => $plan])
  <div class="mt-3">
    <button class="btn btn-primary">حفظ</button>
    <a href="{{ route('admin.plans.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
