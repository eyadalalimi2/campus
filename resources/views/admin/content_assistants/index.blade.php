@extends('admin.layouts.app')

@section('title', 'مساعدين المحتوى')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">مساعدين المحتوى</h3>
        <a href="{{ route('admin.content_assistants.create') }}" class="btn btn-primary">+ إضافة مساعد</a>
    </div>

    <form method="get" class="card card-body mb-3">
        <div class="row g-2">
            <div class="col-md-6">
                <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="بحث بالاسم/الجامعة/الكلية/التخصص">
            </div>
            <div class="col-md-3">
                <select name="is_active" class="form-select">
                    <option value="">حالة الظهور</option>
                    <option value="1" @selected(request('is_active')==='1')>مفعل</option>
                    <option value="0" @selected(request('is_active')==='0')>مخفي</option>
                </select>
            </div>
            <div class="col-md-3 text-end">
                <button class="btn btn-outline-secondary">بحث</button>
            </div>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الصورة</th>
                    <th>الاسم</th>
                    <th>الجامعة</th>
                    <th>الكلية</th>
                    <th>التخصص</th>
                    <th>الترتيب</th>
                    <th>الحالة</th>
                    <th style="width:160px">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assistants as $a)
                    <tr>
                        <td>{{ $a->id }}</td>
                        <td>
                            @if($a->photo_url)
                                <img src="{{ $a->photo_url }}" alt="{{ $a->name }}" style="height:48px;width:48px;object-fit:cover;border-radius:8px">
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $a->name }}</td>
                        <td>{{ $a->university_text ?? '—' }}</td>
                        <td>{{ $a->college_text ?? '—' }}</td>
                        <td>{{ $a->major_text ?? '—' }}</td>
                        <td>{{ $a->sort_order }}</td>
                        <td>
                            @if($a->is_active)
                                <span class="badge bg-success">مفعل</span>
                            @else
                                <span class="badge bg-secondary">مخفي</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.content_assistants.edit', $a) }}" class="btn btn-sm btn-warning">تعديل</a>
                            <form action="{{ route('admin.content_assistants.destroy', $a) }}" method="post" class="d-inline" onsubmit="return confirm('حذف هذا المساعد؟')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center">لا توجد بيانات.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $assistants->links('vendor.pagination.bootstrap-custom') }}
</div>
@endsection
