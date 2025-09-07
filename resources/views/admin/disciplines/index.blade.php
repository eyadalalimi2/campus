@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>المجالات الأكاديمية</h1>
    <a href="{{ route('admin.disciplines.create') }}" class="btn btn-primary mb-3">إضافة مجال</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>نشط؟</th>
                <th>عمليات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($disciplines as $discipline)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $discipline->name }}</td>
                <td>{{ $discipline->is_active ? 'نعم' : 'لا' }}</td>
                <td>
                    <a href="{{ route('admin.disciplines.edit',$discipline) }}" class="btn btn-sm btn-warning">تعديل</a>
                    <form action="{{ route('admin.disciplines.destroy',$discipline) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $disciplines->links() }}
</div>
@endsection
