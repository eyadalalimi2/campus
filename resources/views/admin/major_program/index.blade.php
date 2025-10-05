@extends('admin.layouts.app')

@section('content')
<h1 class="mb-4">الربط بين التخصصات والبرامج</h1>

<a href="{{ route('admin.major_program.create') }}" class="btn btn-primary mb-3">إضافة ربط</a>

<table class="table table-bordered">
  <thead>
    <tr>
      <th>#</th>
      <th>التخصص</th>
      <th>البرنامج</th>
      <th>الاجراءات</th>
    </tr>
  </thead>
  <tbody>
    @foreach($items as $i)
    <tr>
      <td>{{ $i->id }}</td>
      <td>{{ $i->major->name ?? '-' }}</td>
      <td>{{ $i->program->name ?? '-' }}</td>
      <td>
        <a href="{{ route('admin.major_program.edit', $i) }}" class="btn btn-sm btn-warning">تعديل</a>
        <form action="{{ route('admin.major_program.destroy', $i) }}" method="POST" style="display:inline-block">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')">حذف</button>
        </form>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

{{ $items->links('vendor.pagination.bootstrap-custom') }}
@endsection
