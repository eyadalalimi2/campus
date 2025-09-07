@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>البرامج الأكاديمية</h1>
    <a href="{{ route('admin.programs.create') }}" class="btn btn-primary mb-3">إضافة برنامج</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>المجال</th>
                <th>نشط؟</th>
                <th>عمليات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($programs as $program)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $program->name }}</td>
                <td>{{ $program->discipline->name }}</td>
                <td>{{ $program->is_active ? 'نعم' : 'لا' }}</td>
                <td>
                    <a href="{{ route('admin.programs.edit',$program) }}" class="btn btn-sm btn-warning">تعديل</a>
                    <form action="{{ route('admin.programs.destroy',$program) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $programs->links('vendor.pagination.bootstrap-custom') }}
</div>
@endsection
