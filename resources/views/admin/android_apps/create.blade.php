@extends('admin.layouts.app')

@section('content')
    <h3>إضافة تطبيق جديد</h3>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.apps.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.android_apps._form')

                <div class="mt-3">
                    <button class="btn btn-primary">حفظ التطبيق</button>
                    <a href="{{ route('admin.apps.index') }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
@endsection
