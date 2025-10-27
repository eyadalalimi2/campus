@extends('admin.layouts.app')

@section('content')
    <h3>تعديل التطبيق — {{ $app->name }}</h3>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.apps.update', $app) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.android_apps._form')

                <div class="mt-3">
                    <button class="btn btn-primary">حفظ التغييرات</button>
                    <a href="{{ route('admin.apps.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
    <div class="mt-3">
        <a href="{{ route('admin.apps.releases.index', $app) }}" class="btn btn-outline-secondary">إدارة الإصدارات والتحديثات</a>
    </div>
@endsection
