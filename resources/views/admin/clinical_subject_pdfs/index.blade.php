@extends('admin.layouts.app')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 m-0">ملفات PDF للمواد السريرية</h1>
        <a href="{{ route('admin.clinical_subject_pdfs.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            إضافة ملف PDF جديد
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <strong>القائمة</strong>
            <div class="w-50 d-none d-md-block">
                <input type="search" class="form-control" placeholder="بحث سريع بالاسم أو المادة (عمليات البحث المتقدمة يمكن إضافتها لاحقاً)">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:20%">الاسم</th>
                        <th style="width:30%">المحتوى</th>
                        <th>الملف</th>
                        <th>الترتيب</th>
                        <th>المادة السريرية</th>
                        <th class="text-nowrap">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pdfs as $pdf)
                        <tr>
                            <td class="fw-semibold">{{ $pdf->name }}</td>
                            <td class="text-truncate" style="max-width: 380px">{{ $pdf->content }}</td>
                            <td>
                                @if($pdf->file)
                                    <a class="btn btn-sm btn-outline-primary" href="{{ asset('storage/' . $pdf->file) }}" target="_blank">
                                        <i class="bi bi-file-earmark-pdf"></i>
                                        عرض
                                    </a>
                                @else
                                    <span class="badge text-bg-secondary">لا يوجد</span>
                                @endif
                            </td>
                            <td><span class="badge text-bg-light border">{{ $pdf->order }}</span></td>
                            <td>{{ $pdf->clinicalSubject->name ?? '-' }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('admin.clinical_subject_pdfs.edit', $pdf) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                    تعديل
                                </a>
                                <form action="{{ route('admin.clinical_subject_pdfs.destroy', $pdf) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">
                                        <i class="bi bi-trash"></i>
                                        حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">لا توجد ملفات حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @isset($pdfs)
            @if(method_exists($pdfs, 'links'))
                <div class="card-footer bg-white">
                    {{ $pdfs->links('vendor.pagination.bootstrap-custom') }}
                </div>
            @endif
        @endisset
    </div>
@endsection
