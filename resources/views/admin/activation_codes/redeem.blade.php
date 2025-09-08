@extends('admin.layouts.app')
@section('title','تفعيل كود يدوي')

@section('content')
<h4 class="mb-3">تفعيل كود لطالب</h4>
<form action="{{ route('admin.activation_codes.redeem') }}" method="POST" class="card p-3">
  @csrf
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">الكود</label>
      <input type="text" name="code" class="form-control" required placeholder="أدخل الكود">
    </div>
    <div class="col-md-6">
      <label class="form-label">الطالب</label>
      <select name="user_id" class="form-select" required>
        <option value="">— اختر —</option>
        @foreach(\App\Models\User::orderBy('name')->get(['id','name']) as $u)
          <option value="{{ $u->id }}">{{ $u->name }} (ID:{{ $u->id }})</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-success"><i class="bi bi-check2-circle"></i> تفعيل</button>
    <a href="{{ route('admin.activation_codes.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
