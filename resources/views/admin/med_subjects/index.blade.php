@extends('admin.layouts.app')
@section('title', 'المواد')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 m-0">المواد</h1>
        <a href="{{ route('admin.med_subjects.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> جديد</a>
    </div>

    @includeWhen(session('success'), 'admin.partials.flash_success')
    @includeWhen($errors->any(), 'admin.partials.flash_errors', ['errors' => $errors])
    <form method="GET" class="card card-body mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                    placeholder="بحث بالاسم/المستوى">
            </div>
            <div class="col-md-3">
                <select name="scope" class="form-select">
                    <option value="">النطاق — الكل</option>
                    <option value="basic" @selected(request('scope') === 'basic')>Basic</option>
                    <option value="clinical" @selected(request('scope') === 'clinical')>Clinical</option>
                    <option value="both" @selected(request('scope') === 'both')>Both</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">الحالة — الكل</option>
                    <option value="published" @selected(request('status') === 'published')>مفعل</option>
                    <option value="draft" @selected(request('status') === 'draft')>موقوف</option>
                </select>
            </div>
            <div class="col-md-1">
                <select name="sort" class="form-select">
                    <option value="order_index" @selected(request('sort', 'order_index') === 'order_index')>ترتيب</option>
                    <option value="name" @selected(request('sort') === 'name')>الاسم</option>
                    <option value="id" @selected(request('sort') === 'id')>#</option>
                </select>
            </div>
            <div class="col-md-1">
                <select name="dir" class="form-select">
                    <option value="asc" @selected(request('dir', 'asc') === 'asc')>↑</option>
                    <option value="desc" @selected(request('dir') === 'desc')>↓</option>
                </select>
            </div>
        </div>
        <div class="mt-2 d-flex gap-2">
            <button class="btn btn-primary"><i class="bi bi-filter"></i> تصفية</button>
            <a href="{{ route('admin.med_subjects.index') }}" class="btn btn-light"><i class="bi bi-x-lg"></i> تفريغ</a>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>الصورة</th>
                        <th>الاسم</th>
                        <th>النطاق</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>الاجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $s)
                        <tr>
                            <td>{{ $s->id }}</td>
                            <td>
                                @if($s->image_path)
                                    <img src="{{ asset('storage/'.$s->image_path) }}" alt="صورة" style="width:40px;height:40px;object-fit:cover;border-radius:8px;">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->scope }}</td>
                            <td>{{ $s->order_index }}</td>
                            <td>
                                <span class="badge bg-{{ $s->status === 'published' ? 'success' : ($s->status === 'draft' ? 'danger' : 'secondary') }}">
                                    {{ $s->status === 'published' ? 'مفعل' : ($s->status === 'draft' ? 'موقوف' : $s->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.med_subjects.edit', $s) }}"
                                    class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil-square"></i> تعديل</a>
                                <form action="{{ route('admin.med_subjects.destroy', $s) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('حذف؟')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> حذف</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">لا توجد بيانات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $subjects->links('vendor.pagination.bootstrap-custom') }}</div>
@endsection
