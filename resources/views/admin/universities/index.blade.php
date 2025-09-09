@extends('admin.layouts.app')
@section('title', 'الجامعات')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">الجامعات</h4>
        <a href="{{ route('admin.universities.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> جامعة جديدة
        </a>
    </div>

    {{-- بحث بالاسم/العنوان/الهاتف --}}
    <form class="row g-2 mb-3" method="GET" action="{{ route('admin.universities.index') }}">
        <div class="col-auto">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                placeholder="بحث بالاسم   ">
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-secondary">بحث</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th style="width:72px">الشعار</th>
                    <th>الاسم</th>
                    <th>العنوان</th>
                    <th style="width:170px">رقم الهاتف</th>
                    <th>الحالة</th>
                    <th class="text-center" style="width:210px">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($universities as $u)
                    @php
                        // تحديد رابط الشعار:
                        // 1) إن وُجد logo_url في السجل نستخدمه كما هو.
                        // 2) إن وُجد مسار ملف logo_path داخل التخزين العام نستخدم Storage::url().
                        // 3) وإلا نستخدم صورة افتراضية (ضعها عند public/images/logo.png أو بدّل المسار).
                        $logoSrc =
                            $u->logo_url ?: 
                            ($u->logo_path ?? null
                                ? \Illuminate\Support\Facades\Storage::url($u->logo_path)
                                : asset('images/logo.png'));
                    @endphp
                    <tr>
                        <td>
                            <img src="{{ $logoSrc }}" alt="Logo" style="height:40px;width:auto;object-fit:contain">
                        </td>
                        <td class="fw-semibold">{{ $u->name }}</td>
                        <td class="text-muted">{{ $u->address }}</td>
                        <td>
                            @if (!empty($u->phone))
                                <a href="tel:{{ preg_replace('/\s+/', '', $u->phone) }}" class="text-decoration-none">
                                    <i class="bi bi-telephone"></i> {{ $u->phone }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if ($u->is_active)
                                <span class="badge bg-success">مفعل</span>
                            @else
                                <span class="badge bg-secondary">غير مفعل</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <a href="{{ route('admin.universities.edit', $u) }}" class="btn btn-sm btn-outline-primary">
                                تعديل
                            </a>
                            <form action="{{ route('admin.universities.destroy', $u) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف الجامعة؟')">
                                    حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">لا توجد بيانات.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $universities->withQueryString()->links('vendor.pagination.bootstrap-custom') }}
@endsection
