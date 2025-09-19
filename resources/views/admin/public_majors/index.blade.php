@extends('admin.layouts.app')
@section('content')
<h1>التخصصات العامة</h1>
<form method="get" class="mb-3 d-flex gap-2">
  <select name="public_college_id" class="form-select" style="max-width:280px">
    <option value="">— كل الكليات —</option>
    @foreach($colleges as $c)
      <option value="{{ $c->id }}" @selected(request('public_college_id')==$c->id)>{{ $c->name }}</option>
    @endforeach
  </select>
  <button class="btn btn-outline-secondary">تصفية</button>
  <a href="{{ route('admin.public-majors.create') }}" class="btn btn-primary ms-auto">إضافة تخصص عام</a>
</form>
<table class="table table-striped">
  <thead><tr><th>#</th><th>الاسم</th><th>الكلية</th><th>الحالة</th><th>إجراءات</th></tr></thead>
  <tbody>
  @foreach($items as $it)
    <tr>
      <td>{{ $it->id }}</td>
      <td>{{ $it->name }}</td>
      <td>{{ $it->publicCollege?->name }}</td>
      <td>{{ $it->status }}</td>
      <td>
        <a href="{{ route('admin.public-majors.edit',$it) }}" class="btn btn-sm btn-secondary">تعديل</a>
        <form action="{{ route('admin.public-majors.destroy',$it) }}" method="post" class="d-inline">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-warning" onclick="return confirm('أرشفة؟')">أرشفة</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $items->links('vendor.pagination.bootstrap-custom') }}
@endsection