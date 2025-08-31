@extends('admin.layouts.app')
@section('title','إدارة الثيمات')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4>إدارة الثيمات</h4>
  <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addThemeModal">+ إضافة ثيم جديد</a>
</div>

<div class="table-responsive">
<table class="table table-striped align-middle">
  <thead>
    <tr>
      <th>الشعار</th>
      <th>اسم الجامعة</th>
      <th>الألوان</th>
      <th>إجراءات</th>
    </tr>
  </thead>
  <tbody>
    @foreach($universities as $u)
    <tr>
      <td>@if($u->logo_url)<img src="{{ $u->logo_url }}" alt="Logo" style="height:40px">@endif</td>
      <td>{{ $u->name }}</td>
      <td>
        <span class="badge" style="background:{{ $u->primary_color }}">أساسي</span>
        <span class="badge" style="background:{{ $u->secondary_color }}">ثانوي</span>
      </td>
      <td>
        <a href="{{ route('admin.themes.edit', $u) }}" class="btn btn-outline-primary btn-sm">تعديل</a>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>
{{ $universities->links() }}

<!-- نافذة إضافة ثيم -->
<div class="modal fade" id="addThemeModal" tabindex="-1">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="{{ route('admin.themes.store') }}" enctype="multipart/form-data">
      @csrf
      <div class="modal-header"><h5 class="modal-title">إضافة ثيم جديد</h5></div>
      <div class="modal-body">
        <div class="mb-2">
          <label>اسم الجامعة</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>Slug</label>
          <input type="text" name="slug" class="form-control" required>
        </div>
        <div class="mb-2">
          <label>اللون الأساسي</label>
          <input type="color" name="primary_color" class="form-control form-control-color" value="#0d6efd" required>
        </div>
        <div class="mb-2">
          <label>اللون الثانوي</label>
          <input type="color" name="secondary_color" class="form-control form-control-color" value="#6c757d" required>
        </div>
        <div class="mb-2">
          <label>شعار الجامعة</label>
          <input type="file" name="logo" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary">حفظ</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
      </div>
    </form>
  </div>
</div>
@endsection
