@extends('admin.layouts.app')

@section('title', 'محتوى التطبيق')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">محتوى التطبيق</h3>
        <a href="{{ route('admin.app_contents.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> إضافة عنصر
        </a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    @if($contents->isEmpty())
        <div class="card"><div class="card-body text-center text-muted">لا يوجد محتوى بعد.</div></div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th style="width:90px">الصورة</th>
                            <th>العنوان</th>
                            <th style="max-width:420px">الوصف</th>
                            <th>الرابط</th>
                            <th style="width:120px">الترتيب</th>
                            <th style="width:120px">الحالة</th>
                            <th style="width:200px">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contents as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    @if($item->image_url)
                                        <img src="{{ $item->image_url }}" style="width:64px;height:64px;object-fit:cover;border-radius:8px;">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="fw-semibold">{{ $item->title }}</td>
                                <td class="text-break" style="max-width:420px">{{ Str::limit($item->description, 180) }}</td>
                                <td class="text-break">
                                    @if($item->link_url)
                                        <a href="{{ $item->link_url }}" target="_blank">{{ $item->link_url }}</a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $item->sort_order }}</td>
                                <td>
                                    @if($item->is_active)
                                        <span class="badge bg-success">مفعل</span>
                                    @else
                                        <span class="badge bg-secondary">مخفي</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.app_contents.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-edit"></i> تعديل
                                    </a>
                                    <form action="{{ route('admin.app_contents.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('تأكيد الحذف؟');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fa fa-trash"></i> حذف
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot><tr><td colspan="8" class="pt-3">{{ $contents->links('vendor.pagination.bootstrap-custom') }}</td></tr></tfoot>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
