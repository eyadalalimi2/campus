@extends('admin.layouts.app')
@section('title','إنشاء دفعة أكواد')

@section('content')
<h4 class="mb-3">إنشاء دفعة أكواد</h4>

<form action="{{ route('admin.activation_code_batches.store') }}" method="POST" class="card p-3">
  @csrf
  @include('admin.activation_codes.batches.form', ['batch' => null])
  <div class="mt-3 d-flex gap-2">
    <button name="generate_now" value="1" class="btn btn-outline-primary"> توليد الأكواد الآن</button>
    <a href="{{ route('admin.activation_code_batches.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
