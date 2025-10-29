@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>الدورات والأنشطة</h3>
            <a href="{{ route('admin.activity_buttons.create') }}" class="btn btn-primary">إضافة زر جديد</a>
        </div>

        <div class="card shadow-sm border-0 bg-white">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr class="table-light text-muted">
                            <th class="px-3 py-2 text-center" style="width:6%">المعرف</th>
                            <th class="px-3 py-2" style="width:39%">العنوان</th>
                            <th class="px-3 py-2 text-center" style="width:15%">عدد الفيديوهات</th>
                            <th class="px-3 py-2 text-center" style="width:10%">الترتيب</th>
                            <th class="px-3 py-2 text-center" style="width:30%">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buttons as $b)
                            <tr>
                                <td class="px-3 py-2 align-middle text-center text-muted">{{ $b->id }}</td>
                                <td class="px-3 py-2 align-middle text-dark">{{ $b->title }}</td>
                                <td class="px-3 py-2 align-middle text-center text-muted">{{ $b->videos()->count() }}</td>
                                <td class="px-3 py-2 align-middle text-center text-muted">{{ $b->order }}</td>
                                <td class="px-3 py-2 align-middle text-center text-nowrap">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        <a href="{{ route('admin.activity_buttons.videos.index', $b->id) }}" class="btn btn-sm btn-primary text-white">فيديوهات</a>
                                        <a href="{{ route('admin.activity_buttons.edit', $b->id) }}" class="btn btn-sm btn-secondary text-white">تعديل</a>
                                        <form action="{{ route('admin.activity_buttons.destroy', $b->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل متأكد من حذف هذا الزر وكل الفيديوهات المرتبطة به؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger text-white">حذف</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
