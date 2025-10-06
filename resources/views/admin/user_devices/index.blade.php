@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h1>الأجهزة المسجلة للمستخدمين</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>المستخدم</th>
                <th>اسم الجهاز</th>
                <th>رمز الجهاز</th>
                <th>تاريخ التسجيل</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($devices as $device)
                <tr>
                    <td>{{ $device->id }}</td>
                    <td>{{ $device->user ? $device->user->name : '-' }}</td>
                    <td>{{ $device->device_name }}</td>
                    <td>{{ $device->device_token }}</td>
                    <td>{{ $device->created_at }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.user_devices.destroy', $device->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $devices->links() }}
</div>
@endsection
