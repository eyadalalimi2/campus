@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <h3>تعديل زر: {{ $activity_button->title }}</h3>

        <div class="card mt-3">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.activity_buttons.update', $activity_button->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">العنوان</label>
                        <input type="text" name="title" class="form-control" required value="{{ old('title', $activity_button->title) }}">
                        @error('title')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    {{-- تم إزالة حقل الـslug من الواجهة؛ يتم توليده تلقائياً عند الحفظ --}}

                    <div class="mb-3">
                        <label class="form-label">الترتيب (رقم)</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', $activity_button->order) }}">
                    </div>

                    <div>
                        <button class="btn btn-primary">حفظ</button>
                        <a href="{{ route('admin.activity_buttons.index') }}" class="btn btn-secondary">إلغاء</a>
                        <button type="submit" form="delete-form" class="btn btn-danger ms-2">حذف</button>
                    </div>
                </form>

                <form id="delete-form" action="{{ route('admin.activity_buttons.destroy', $activity_button->id) }}" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
@endsection
