@extends('admin.layouts.app')

@section('content')
    <h3>تعديل بحث / رسالة ماجستير</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.research_pdfs.update', $pdf) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">العنوان</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $pdf->title) }}" required />
        </div>
        <div class="mb-3">
            <label class="form-label">الترتيب (رقم)</label>
            <input type="number" name="order" class="form-control" value="{{ old('order', $pdf->order ?? 0) }}" />
        </div>
        <div class="mb-3">
            <label class="form-label">الملخص (اختياري)</label>
            <textarea name="abstract" class="form-control" rows="4">{{ old('abstract', $pdf->abstract) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">ملف PDF (استبدال اختياري)</label>
            @if($pdf->file)
                <div class="mb-2">
                    <a href="{{ asset('storage/' . $pdf->file) }}" target="_blank" class="btn btn-sm btn-outline-primary">فتح الملف الحالي</a>
                </div>
            @endif
            <input type="file" name="file" accept="application/pdf" class="form-control" />
        </div>

        <button class="btn btn-primary">تحديث</button>
        <a href="{{ route('admin.research_pdfs.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
@endsection
