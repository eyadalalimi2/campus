@extends('admin.layouts.app')

@section('title', 'أجهزة المستخدمين')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">أجهزة المستخدمين</h4>
        <form method="GET" action="{{ route('admin.user_devices.index') }}" class="d-flex" style="gap:.5rem">
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="بحث: اسم/بريد/جهاز/موديل/IP/المعرف">
            <button class="btn btn-primary">بحث</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>المستخدم</th>
                            <th>البريد</th>
                            <th>اسم الجهاز</th>
                            <th>الموديل</th>
                            <th>معرف الجهاز</th>
                            <th>عنوان IP</th>
                            <th>آخر تسجيل دخول</th>
                            <th class="text-end">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($devices as $device)
                            <tr>
                                <td>{{ $device->id }}</td>
                                <td>{{ $device->user?->name }}</td>
                                <td>{{ $device->user?->email }}</td>
                                <td>{{ $device->device_name }}</td>
                                <td>{{ $device->device_model ?: '—' }}</td>
                                <td style="max-width:220px">{{ $device->device_uuid ?: '—' }}</td>
                                <td>{{ $device->ip_address ?: '—' }}</td>
                                <td>{{ optional($device->last_login_at)->format('Y-m-d H:i') ?: '—' }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('admin.user_devices.destroy', $device) }}"
                                          onsubmit="return confirm('هل أنت متأكد من حذف ربط هذا الجهاز؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> حذف
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">لا توجد أجهزة مسجلة.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $devices->links('vendor.pagination.bootstrap-custom') }}
            </div>
        </div>
    </div>
</div>
@endsection
