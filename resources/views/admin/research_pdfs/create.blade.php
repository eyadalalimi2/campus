@extends('admin.layouts.app')

@section('content')
    <h3>إضافة بحث / رسالة ماجستير</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.research_pdfs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">العنوان</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required />
        </div>
        <div class="mb-3">
            <label class="form-label">الترتيب (رقم)</label>
            <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" />
        </div>
        <div class="mb-3">
            <label class="form-label">الملخص (اختياري)</label>
            <textarea name="abstract" class="form-control" rows="4">{{ old('abstract') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">ملف PDF</label>
            <input type="file" name="file" accept="application/pdf" class="form-control" required />
        </div>

        <button class="btn btn-primary">حفظ</button>
        <a href="{{ route('admin.research_pdfs.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
@endsection
