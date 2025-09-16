@extends('admin.layouts.app')
@section('title','الإشعارات')

@section('content')
@php
  $typeLabels = [
    'content_created' => 'تم إنشاء محتوى',
    'content_updated' => 'تم تحديث محتوى',
    'content_deleted' => 'تم حذف محتوى',
    'asset_created'   => 'تم إنشاء مادة تعليمية',
    'asset_updated'   => 'تم تحديث مادة تعليمية',
    'asset_deleted'   => 'تم حذف مادة تعليمية',
    'system'          => 'نظام',
    'other'           => 'أخرى',
  ];
@endphp

<h1 class="h4 mb-3">الإشعارات</h1>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-2">
    <label class="form-label">نوع الإشعار</label>
    <select name="type" class="form-select" onchange="this.form.submit()">
      <option value="">الكل</option>
      @foreach($typeLabels as $key=>$label)
        <option value="{{ $key }}" @selected(request('type')===$key)>{{ $label }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-2">
    <label class="form-label">حالة القراءة</label>
    <select name="read" class="form-select" onchange="this.form.submit()">
      <option value="">الكل</option>
      <option value="0" @selected(request('read')==='0')>غير مقروء</option>
      <option value="1" @selected(request('read')==='1')>مقروء</option>
    </select>
  </div>
  <div class="col-md-2">
    <label class="form-label">معرّف المستخدم</label>
    <input type="number" name="user_id" class="form-control" value="{{ request('user_id') }}" placeholder="ID">
  </div>
  <div class="col-md-4">
    <label class="form-label">بحث</label>
    <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="العنوان/المحتوى">
  </div>
  <div class="col-md-2 d-flex align-items-end">
    <button class="btn btn-outline-secondary w-100">تصفية</button>
  </div>
</form>

<div class="mb-3 d-flex gap-2">
  <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary">إنشاء إشعار</a>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>المستخدم</th>
          <th>العنوان</th>
          <th>النوع</th>
          <th>الهدف</th>
          <th>الرابط</th>
          <th>الحالة</th>
          <th>التاريخ</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($notifications as $n)
          @php
            $__data = is_array($n->data) ? $n->data : ($n->data ? json_decode($n->data, true) : []);
            $__action = $__data['action_url'] ?? null;
            $__label = $typeLabels[$n->type] ?? $n->type;
          @endphp
          <tr>
            <td>{{ $n->id }}</td>
            <td class="small">
              {{ $n->user?->name ?? '—' }}
              <div class="text-muted">{{ $n->user?->email ?? '—' }}</div>
            </td>
            <td class="text-truncate" style="max-width:280px">{{ $n->title }}</td>
            <td><span class="badge bg-info-subtle text-dark">{{ $__label }}</span></td>
            <td class="small">
              {{ $n->target_type ?? '—' }} @if($n->target_id) #{{ $n->target_id }} @endif
            </td>
            <td>
              @if($__action)
                <a href="{{ $__action }}" target="_blank" class="btn btn-sm btn-outline-primary">فتح</a>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>
              @if($n->read_at)
                <span class="badge bg-success">مقروء</span>
              @else
                <span class="badge bg-secondary">غير مقروء</span>
              @endif
            </td>
            <td class="small text-muted">{{ optional($n->created_at)->format('Y-m-d H:i') }}</td>
            <td class="text-nowrap">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.notifications.show',$n) }}">عرض</a>
              <form method="POST" action="{{ route('admin.notifications.destroy',$n) }}" class="d-inline"
                    onsubmit="return confirm('حذف الإشعار؟');">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">حذف</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="9" class="text-center text-muted">لا توجد إشعارات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">
    {{ $notifications->links() }}
  </div>
</div>
@endsection
