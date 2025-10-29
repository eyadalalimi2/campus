@extends('admin.layouts.app')

@section('content')
    <h3>تعديل النصيحة</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.medical_tips.update', $tip) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">العنوان</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $tip->title) }}" required />
        </div>
        <div class="mb-3">
            <label class="form-label">الترتيب (رقم) — أصغر قيمة تظهر أولاً</label>
            <input type="number" name="order" class="form-control" value="{{ old('order', $tip->order ?? 0) }}" />
        </div>
        <div class="mb-3">
            <label class="form-label">الوصف المختصر</label>
            <textarea name="short_description" class="form-control" rows="3">{{ old('short_description', $tip->short_description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">رابط يوتيوب</label>
            <input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $tip->youtube_url) }}" required />
        </div>
        <div class="mb-3">
            <label class="form-label">صورة الغلاف (اختياري)</label>
            @if($tip->cover)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $tip->cover) }}" alt="cover" style="width:120px;height:80px;object-fit:cover;border-radius:4px;" />
                </div>
            @endif
            <input type="file" name="cover" accept="image/*" class="form-control" />
        </div>

        <button class="btn btn-primary">تحديث</button>
        <a href="{{ route('admin.medical_tips.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
@endsection
