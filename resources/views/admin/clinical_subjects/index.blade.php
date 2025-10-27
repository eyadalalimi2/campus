@extends('admin.layouts.app')
@section('content')
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 m-0">المواد السريرية</h1>
        <a href="{{ route('admin.clinical_subjects.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i>
            إضافة مادة جديدة
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex align-items-center justify-content-between">
            <strong>القائمة</strong>
            <div class="w-50 d-none d-md-block">
                <input type="search" class="form-control" placeholder="بحث سريع بالاسم (يمكن إضافة فلترة لاحقاً)">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>الاسم</th>
                        <th>الصورة</th>
                        <th>الترتيب</th>
                        <th class="text-nowrap">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                        <tr>
                            <td class="fw-semibold">{{ $subject->name }}</td>
                            <td>
                                @if($subject->image)
                                    <img src="{{ asset('storage/' . $subject->image) }}" class="rounded border" style="width:48px;height:48px;object-fit:cover" alt="صورة">
                                @else
                                    <span class="badge text-bg-secondary">لا يوجد</span>
                                @endif
                            </td>
                            <td><span class="badge text-bg-light border">{{ $subject->order }}</span></td>
                            <td class="text-nowrap">
                                <a href="{{ route('admin.clinical_subjects.edit', $subject) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                    تعديل
                                </a>
                                <form action="{{ route('admin.clinical_subjects.destroy', $subject) }}" method="POST" class="d-inline">
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
                            <td colspan="4" class="text-center text-muted py-5">لا توجد مواد حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @isset($subjects)
            @if(method_exists($subjects, 'links'))
                <div class="card-footer bg-white">
                    {{ $subjects->links('vendor.pagination.bootstrap-custom') }}
                </div>
            @endif
        @endisset
    </div>
@endsection
