@extends('admin.layouts.app')
@section('title','تفعيل كود لطالب')

@section('content')
<h4 class="mb-3">تفعيل كود لطالب</h4>

<form action="{{ route('admin.subscriptions.store') }}" method="POST" class="card p-3">
  @csrf

  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">الطالب <span class="text-danger">*</span></label>
      <select name="user_id" class="form-select" required>
        <option value="">— اختر —</option>
        @foreach($users as $u)
          <option value="{{ $u->id }}" @selected(old('user_id')==$u->id)>{{ $u->name }} — {{ $u->email }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">كود التفعيل (10 أرقام) <span class="text-danger">*</span></label>
      <input type="text" name="activation_code" class="form-control" required pattern="\d{10}"
             placeholder="مثال: 0123456789" value="{{ old('activation_code') }}">
      <div class="form-text">لن تُقبل إلا أكواد من 10 أرقام.</div>
    </div>
  </div>

  <div class="mt-3">
    <button class="btn btn-primary"><i class="bi bi-check2-circle"></i> تفعيل</button>
    <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-link">رجوع</a>
  </div>
</form>
@endsection
