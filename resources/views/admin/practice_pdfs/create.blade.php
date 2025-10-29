@extends('admin.layouts.app')

@section('content')
    <h3>إضافة ملف PDF لاختبار مزاولة المهنة</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.practice_pdfs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">الاسم</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required />
        </div>
        <div class="mb-3">
            <label class="form-label">الترتيب (رقم)</label>
            <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" />
        </div>
        <div class="mb-3">
            <label class="form-label">الوصف (اختياري)</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">ملف PDF</label>
            <input type="file" name="file" accept="application/pdf" class="form-control" required />
        </div>

        <button class="btn btn-primary">حفظ</button>
        <a href="{{ route('admin.practice_pdfs.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
@endsection
