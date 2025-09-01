@extends('admin.layouts.app')
@section('title','إدارة الثيمات')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">إدارة الثيمات</h4>
</div>

<div class="row g-3">
  @forelse($universities as $u)
    <div class="col-md-6 col-lg-4">
      <div class="card shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <img src="{{ $u->logo_url }}" style="height:44px;object-fit:contain">
          <div class="flex-grow-1">
            <div class="fw-semibold">{{ $u->name }}</div>
            <div class="small text-muted">{{ $u->address }}</div>
          </div>
          <a href="{{ route('admin.themes.edit',$u) }}" class="btn btn-outline-primary btn-sm">تعديل الثيم</a>
        </div>
      </div>
    </div>
  @empty
    <div class="col-12 text-muted text-center">لا توجد جامعات.</div>
  @endforelse
</div>
@endsection
