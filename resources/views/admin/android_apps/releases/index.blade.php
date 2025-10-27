@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>إصدارات: {{ $app->name }}</h3>
        <a href="{{ route('admin.apps.releases.create', $app) }}" class="btn btn-primary">رفع إصدار جديد</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>النسخة</th>
                        <th>code</th>
                        <th>التاريخ</th>
                        <th>الحجم</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($releases as $r)
                        <tr>
                            <td>{{ $r->version_name }}</td>
                            <td>{{ $r->version_code }}</td>
                            <td>{{ $r->published_at ? $r->published_at->format('Y-m-d') : $r->created_at->format('Y-m-d') }}</td>
                            <td>{{ $r->apk_size }}</td>
                            <td class="text-end">
                                <a href="{{ route('apps.download', $app->slug) }}?release_id={{ $r->id }}" class="btn btn-sm btn-outline-success">تحميل هذا الإصدار</a>
                                <form action="{{ route('admin.releases.destroy', $r) }}" method="POST" class="d-inline-block" onsubmit="return confirm('حذف هذا الإصدار؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
