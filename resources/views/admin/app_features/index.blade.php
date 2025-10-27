@extends('admin.layouts.app')

@section('title', 'مميّزات التطبيق')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">مميّزات التطبيق</h3>
        <a href="{{ route('admin.app_features.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> إضافة ميزة
        </a>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($features->count() === 0)
        <div class="card">
            <div class="card-body text-center text-muted">
                لا توجد مميزات بعد.
            </div>
        </div>
    @else
        <div class="card">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th style="width: 90px;">الصورة</th>
                            <th>النص</th>
                            <th style="width: 120px;">الترتيب</th>
                            <th style="width: 120px;">الحالة</th>
                            <th style="width: 180px;">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($features as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    @if($item->image_url)
                                        <img src="{{ $item->image_url }}" alt="image" style="width:64px;height:64px;object-fit:cover;border-radius:8px;">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-break" style="max-width: 500px;">{{ $item->text }}</td>
                                <td>{{ $item->sort_order }}</td>
                                <td>
                                    @if($item->is_active)
                                        <span class="badge bg-success">مفعل</span>
                                    @else
                                        <span class="badge bg-secondary">مخفي</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.app_features.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fa fa-edit"></i> تعديل
                                    </a>
                                    <form action="{{ route('admin.app_features.destroy', $item) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('تأكيد حذف هذه الميزة؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="fa fa-trash"></i> حذف
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">
                                <div class="mt-3">
                                    {{ $features->links('vendor.pagination.bootstrap-custom') }}
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
