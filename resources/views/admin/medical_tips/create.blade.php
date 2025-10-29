@extends('admin.layouts.app')

@section('content')
    <h3>إضافة نصيحة طبية جديدة</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.medical_tips.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">العنوان</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required />
        </div>
        <div class="mb-3">
            <label class="form-label">الترتيب (رقم) — أصغر قيمة تظهر أولاً</label>
            <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" />
        </div>
        <div class="mb-3">
            <label class="form-label">الوصف المختصر</label>
            <textarea name="short_description" class="form-control" rows="3">{{ old('short_description') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">رابط يوتيوب</label>
            <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url') }}" required />
        </div>
        <div class="mb-3">
            <label class="form-label">صورة الغلاف (اختياري)</label>
            <input type="file" name="cover" accept="image/*" class="form-control" />
        </div>

        <button class="btn btn-primary">حفظ</button>
        <a href="{{ route('admin.medical_tips.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
@endsection
