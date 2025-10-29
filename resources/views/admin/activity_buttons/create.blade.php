@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <h3>إضافة زر جديد</h3>

        <div class="card mt-3">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.activity_buttons.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">العنوان</label>
                        <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                        @error('title')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    {{-- slug محذوف من الواجهة لأنّه يُنشأ تلقائياً في الـ Controller --}}

                    <div class="mb-3">
                        <label class="form-label">الترتيب (رقم)</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}">
                    </div>

                    <div>
                        <button class="btn btn-primary">حفظ</button>
                        <a href="{{ route('admin.activity_buttons.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
