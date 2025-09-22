@extends('admin.layouts.app')

@section('title', 'إدارة الدول')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">الدول</h4>
    <a href="{{ route('admin.countries.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> إضافة دولة</a>
</div>

<div class="table-responsive">
    <table class="table table-hover bg-white align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>الاسم بالعربية</th>
                <th>الرمز ISO2</th>
                <th>رمز الهاتف</th>
                <th>نشط؟</th>
                <th class="text-center">إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($countries as $country)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $country->name_ar }}</td>
                <td>{{ $country->iso2 ?: '—' }}</td>
                <td>{{ $country->phone_code ?: '—' }}</td>
                <td>
                    {!! $country->is_active
                        ? '<span class="badge bg-success">نعم</span>'
                        : '<span class="badge bg-secondary">لا</span>' !!}
                </td>
                <td class="text-center">
                    <a href="{{ route('admin.countries.edit', $country) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                    <form action="{{ route('admin.countries.destroy', $country) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف الدولة؟')">حذف</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted">لا توجد بيانات.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $countries->links('vendor.pagination.bootstrap-custom') }}
</div>
@endsection
