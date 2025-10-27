@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>تطبيقات الأندرويد</h3>
        <a href="{{ route('admin.apps.create') }}" class="btn btn-primary">إضافة تطبيق جديد</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الأيقونة</th>
                        <th>الاسم</th>
                        <th>الـ slug</th>
                        <th>النسخة</th>
                        <th>التحميلات</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($apps as $app)
                    <tr>
                        <td style="width:64px">
                            @if($app->icon_path)
                                <img src="{{ Storage::url($app->icon_path) }}" alt="" style="height:48px; width:48px; object-fit:cover; border-radius:8px">
                            @endif
                        </td>
                        <td>{{ $app->name }}</td>
                        <td>{{ $app->slug }}</td>
                        <td>{{ $app->version_name }}</td>
                        <td>{{ $app->downloads_total }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.apps.releases.index', $app) }}" class="btn btn-sm btn-outline-info me-1">عرض الإصدارات</a>
                            <a href="{{ route('admin.apps.releases.create', ['app' => $app->id]) }}" class="btn btn-sm btn-outline-success me-1">رفع تحديث</a>
                            <a href="{{ route('admin.apps.edit', $app) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                            <form action="{{ route('admin.apps.destroy', $app) }}" method="POST" class="d-inline-block ms-1"
                                  onsubmit="return confirm('هل تريد حذف التطبيق؟');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $apps->links() }}
        </div>
    </div>
@endsection
