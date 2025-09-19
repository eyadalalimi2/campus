@extends('admin.layouts.app')
@section('content')
<h1>الكليات العامة</h1>
<a href="{{ route('admin.public-colleges.create') }}" class="btn btn-primary mb-3">إضافة كلية عامة</a>
<table class="table table-striped">
  <thead><tr><th>#</th><th>الاسم</th><th>الحالة</th><th>إجراءات</th></tr></thead>
  <tbody>
  @foreach($items as $it)
    <tr>
      <td>{{ $it->id }}</td>
      <td>{{ $it->name }}</td>
      <td>{{ $it->status }}</td>
      <td>
        <a href="{{ route('admin.public-colleges.edit',$it) }}" class="btn btn-sm btn-secondary">تعديل</a>
        <form action="{{ route('admin.public-colleges.destroy',$it) }}" method="post" class="d-inline">
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
