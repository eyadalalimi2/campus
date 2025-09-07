@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>الفصول الأكاديمية</h1>
    <a href="{{ route('admin.academic-terms.create') }}" class="btn btn-primary mb-3">إضافة فصل جديد</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>التقويم</th>
                <th>الجامعة</th>
                <th>الفصل</th>
                <th>البداية</th>
                <th>النهاية</th>
                <th>نشط؟</th>
                <th>عمليات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($terms as $term)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $term->calendar->year_label }}</td>
                <td>{{ $term->calendar->university->name }}</td>
                <td>{{ $term->name }}</td>
                <td>{{ $term->starts_on->format('Y-m-d') }}</td>
                <td>{{ $term->ends_on->format('Y-m-d') }}</td>
                <td>{{ $term->is_active ? 'نعم' : 'لا' }}</td>
                <td>
                    <a href="{{ route('admin.academic-terms.edit',$term) }}" class="btn btn-sm btn-warning">تعديل</a>
                    <form action="{{ route('admin.academic-terms.destroy',$term) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $terms->links('vendor.pagination.bootstrap-custom') }}
</div>
@endsection
