@extends('admin.layouts.app')
@section('title', 'المواضيع')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h4 m-0">المواضيع</h1>
        <a href="{{ route('admin.med_topics.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> جديد</a>
    </div>

    @includeWhen(session('success'), 'admin.partials.flash_success')
    @includeWhen($errors->any(), 'admin.partials.flash_errors', ['errors' => $errors])
    <form method="GET" class="card card-body mb-3">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                    placeholder="بحث بالعنوان/الوصف">
            </div>
            <div class="col-md-3">
                <select name="subject_id" class="form-select">
                    <option value="">المادة — الكل</option>
                    @foreach ($subjects as $s)
                        <option value="{{ $s->id }}" @selected(request('subject_id') == $s->id)>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">الحالة — الكل</option>
                    <option value="published" @selected(request('status') === 'published')>مفعل</option>
                    <option value="draft" @selected(request('status') === 'draft')>موقوف</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="order_index" @selected(request('sort', 'order_index') === 'order_index')>ترتيب</option>
                    <option value="title" @selected(request('sort') === 'title')>العنوان</option>
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
            <a href="{{ route('admin.med_topics.index') }}" class="btn btn-light"><i class="bi bi-x-lg"></i> تفريغ</a>
        </div>
    </form>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>المادة</th>
                        <th>الترتيب</th>
                        <th>الحالة</th>
                        <th>الاجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topics as $t)
                        <tr>
                            <td>{{ $t->id }}</td>
                            <td>{{ $t->title }}</td>
                            <td>{{ $t->subject?->name }}</td>
                            <td>{{ $t->order_index }}</td>
                            <td>
                                <span class="badge bg-{{ $t->status === 'published' ? 'success' : 'secondary' }}">
                                    {{ $t->status === 'published' ? 'مفعل' : 'موقوف' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.med_topics.edit', $t) }}" class="btn btn-sm btn-outline-primary"><i
                                        class="bi bi-pencil-square"></i> تعديل</a>
                                <form action="{{ route('admin.med_topics.destroy', $t) }}" method="POST" class="d-inline"
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

    <div class="mt-3">{{ $topics->links('vendor.pagination.bootstrap-custom') }}</div>
@endsection
