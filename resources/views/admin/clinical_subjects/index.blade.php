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
                <form method="get" class="d-flex">
                    <input name="q" value="{{ request('q') }}" type="search" class="form-control" placeholder="بحث سريع بالاسم">
                    <select name="is_active" class="form-select ms-2" style="max-width:120px">
                        <option value="">الكل</option>
                        <option value="1" @selected(request('is_active')==='1')>مفعل</option>
                        <option value="0" @selected(request('is_active')==='0')>معطل</option>
                    </select>
                    <button class="btn btn-primary ms-2">تصفية</button>
                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary ms-2">مسح</a>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>المعرف</th>
                        <th>الاسم</th>
                        <th>الصورة</th>
                        <th>الترتيب</th>
                        <th class="text-nowrap">العمليات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                        <tr>
                            <td>{{ $subject->id }}</td>
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
                            <td colspan="5" class="text-center text-muted py-5">لا توجد مواد حالياً.</td>
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
