@extends('admin.layouts.app')
@section('title','الإشعارات')

@section('content')
<div class="d-flex align-items-center mb-3">
  <h1 class="h4 mb-0">الإشعارات</h1>
  <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary ms-auto">+ إرسال إشعار</a>
</div>

<form method="GET" class="row g-2 mb-3">
  <div class="col-md-3">
    <label class="form-label">النوع</label>
    <select name="target_type" class="form-select" onchange="this.form.submit()">
      <option value="">الكل</option>
      <option value="all" @selected(request('target_type')==='all')>لكل المستخدمين</option>
      <option value="user" @selected(request('target_type')==='user')>لمستخدم محدد</option>
      <option value="major" @selected(request('target_type')==='major')>لتخصص</option>
      <option value="university" @selected(request('target_type')==='university')>لجامعة</option>
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">بحث</label>
    <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="العنوان/النص">
  </div>
  <div class="col-md-3 d-flex align-items-end">
    <button class="btn btn-outline-secondary w-100">تصفية</button>
  </div>
</form>

<div class="card">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>العنوان</th>
          <th>النص</th>
          <th>الهدف</th>
          <th>أنشئ</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($notifications as $n)
          <tr>
            <td>{{ $n->id }}</td>
            <td>{{ $n->title }}</td>
            <td class="text-truncate" style="max-width:360px">{{ $n->body }}</td>
            <td>
              <span class="badge bg-info-subtle text-dark">{{ $n->target_type }}</span>
              @if($n->target_id)<div class="small text-muted">#{{ $n->target_id }}</div>@endif
            </td>
            <td class="small text-muted">{{ $n->created_at?->format('Y-m-d H:i') }}</td>
            <td class="text-nowrap">
              <a href="{{ route('admin.notifications.show',$n) }}" class="btn btn-sm btn-outline-primary">عرض</a>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted">لا توجد إشعارات</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">
    {{ $notifications->withQueryString()->links() }}
  </div>
</div>
@endsection
