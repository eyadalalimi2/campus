@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">ملفات اختبار مزاولة المهنة</h3>
        <a href="{{ route('admin.practice_pdfs.create') }}" class="btn btn-primary">إضافة ملف PDF</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th style="width:60px;">المعرف</th>
                    <th style="width:80px;">الترتيب</th>
                    <th>الاسم</th>
                    <th style="width:240px;">الوصف</th>
                    <th style="width:180px;" class="text-nowrap">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pdfs as $pdf)
                    <tr>
                        <td>{{ $pdf->id }}</td>
                        <td>{{ $pdf->order }}</td>
                        <td>{{ $pdf->name }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($pdf->description, 120) }}</td>
                        <td class="text-nowrap">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.practice_pdfs.edit', $pdf) }}" class="btn btn-sm btn-secondary">تعديل</a>
                                <form action="{{ route('admin.practice_pdfs.destroy', $pdf) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">حذف</button>
                                </form>
                                <a href="{{ asset('storage/' . $pdf->file) }}" target="_blank" class="btn btn-sm btn-success">عرض PDF</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $pdfs->links() }}
    </div>
@endsection
