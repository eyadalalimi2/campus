@extends('admin.layouts.app')

@section('title','إدارة الكورسات')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>إدارة الكورسات</h4>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">إضافة كورس جديد</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>عنوان الكورس</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>{{ $course->id }}</td>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->sort_order }}</td>
                            <td>
                                @if($course->is_active)
                                    <span class="badge bg-success">مفعل</span>
                                @else
                                    <span class="badge bg-danger">معطل</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.courses.edit',$course->id) }}" class="btn btn-sm btn-warning">تعديل</a>
                                <form action="{{ route('admin.courses.destroy',$course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">لا توجد كورسات مضافة</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
