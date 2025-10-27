@extends('admin.layouts.app')

@section('content')
    <h3>رفع إصدار جديد: {{ $app->name }}</h3>

    <div class="card mt-3">
        <div class="card-body">
            <form action="{{ route('admin.apps.releases.store', $app) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">version_name</label>
                        <input name="version_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">version_code</label>
                        <input name="version_code" type="number" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ملف APK</label>
                        <input type="file" name="apk_file" class="form-control" accept=".apk" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">apk_size (مثال: 25.4 MB)</label>
                        <input name="apk_size" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">المميزات / سجل التغييرات</label>
                        <textarea name="changelog" rows="4" class="form-control"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">تاريخ النشر</label>
                        <input name="published_at" type="date" class="form-control">
                    </div>
                </div>

                <div class="mt-3">
                    <button class="btn btn-primary">رفع وحفظ الإصدار</button>
                    <a href="{{ route('admin.apps.releases.index', $app) }}" class="btn btn-secondary">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
@endsection
