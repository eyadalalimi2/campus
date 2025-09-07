@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>التقاويم الأكاديمية</h1>
    <a href="{{ route('admin.academic_calendars.create') }}" class="btn btn-primary mb-3">إضافة تقويم جديد</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>الجامعة</th>
                <th>العام الدراسي</th>
                <th>البداية</th>
                <th>النهاية</th>
                <th>نشط؟</th>
                <th>عمليات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($calendars as $calendar)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $calendar->university->name }}</td>
                <td>{{ $calendar->year_label }}</td>
                <td>{{ $calendar->starts_on->format('Y-m-d') }}</td>
                <td>{{ $calendar->ends_on->format('Y-m-d') }}</td>
                <td>{{ $calendar->is_active ? 'نعم' : 'لا' }}</td>
                <td>
                    <a href="{{ route('admin.academic_calendars.edit',$calendar) }}" class="btn btn-sm btn-warning">تعديل</a>
                    <form action="{{ route('admin.academic_calendars.destroy',$calendar) }}" method="POST" class="d-inline-block" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $calendars->links() }}
</div>
@endsection
