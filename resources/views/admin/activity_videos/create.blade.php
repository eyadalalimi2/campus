@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <h3>إضافة فيديو لـ: {{ $activity_button->title }}</h3>

        <div class="card mt-3">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.activity_buttons.videos.store', $activity_button->id) }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">العنوان</label>
                        <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                        @error('title')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">رابط يوتيوب</label>
                        <input type="text" name="youtube_url" class="form-control" required value="{{ old('youtube_url') }}">
                        @error('youtube_url')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">وصف قصير</label>
                        <textarea name="short_description" class="form-control">{{ old('short_description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">غلاف الفيديو (صورة)</label>
                        <input type="file" name="cover_image" accept="image/*" class="form-control">
                        @error('cover_image')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الترتيب</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}">
                    </div>

                    <div>
                        <button class="btn btn-primary">حفظ</button>
                        <a href="{{ route('admin.activity_buttons.videos.index', $activity_button->id) }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
