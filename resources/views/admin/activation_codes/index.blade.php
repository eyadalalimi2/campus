@extends('admin.layouts.app')
@section('title', 'الأكواد الفردية')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">الأكواد الفردية</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.activation_codes.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> كود جديد</a>
            <a href="{{ route('admin.activation_codes.redeem_form') }}" class="btn btn-outline-success"><i class="bi bi-key"></i> تفعيل يدوي</a>
            <a href="{{ route('admin.activation_code_batches.index') }}" class="btn btn-outline-info"><i class="bi bi-collection"></i> دفعات الاكواد</a>
        </div>
    </div>

    <form class="row g-2 mb-3">
        <div class="col-md-3"><input type="text" name="q" class="form-control" value="{{ request('q') }}"
                placeholder="بحث بالكود"></div>
        <div class="col-md-2">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">الحالة</option>
                @foreach (['active', 'redeemed', 'expired', 'disabled'] as $st)
                    <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="batch_id" class="form-select" onchange="this.form.submit()">
                <option value="">الدفعة</option>
                @foreach ($batches as $b)
                    <option value="{{ $b->id }}" @selected(request('batch_id') == $b->id)>{{ $b->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" name="created_from" id="created_from" class="form-control js-date" placeholder="من تاريخ"
                value="{{ request('created_from') }}">
        </div>
        <div class="col-md-2">
            <input type="text" name="created_to" id="created_to" class="form-control js-date" placeholder="إلى تاريخ"
                value="{{ request('created_to') }}">
        </div>

        <div class="col-md-1"><button class="btn btn-outline-secondary w-100">بحث</button></div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover bg-white align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>الكود</th>
                    <th>الدفعة</th>
                    <th>الخطة</th>
                    <th>النطاق</th>
                    <th>المدة</th>
                    <th>الحالة</th>
                    <th class="text-center">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($codes as $c)
                    <tr>
                        <td>{{ $c->id }}</td>
                        <td class="fw-semibold">{{ $c->code }}</td>
                        <td>{{ $c->batch?->name ?: '—' }}</td>
                        <td>{{ $c->plan->name ?? '#' . $c->plan_id }}</td>
                        <td class="small text-muted">
                            {{ $c->university->name ?? '—' }}
                            @if ($c->college)
                                / {{ $c->college->name }}
                            @endif
                            @if ($c->major)
                                / {{ $c->major->name }}
                            @endif
                        </td>
                        <td>{{ $c->duration_days }} يوم</td>
                        <td>{{ $c->status }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.activation_codes.edit', $c) }}"
                                class="btn btn-sm btn-outline-primary">تعديل</a>
                            <form action="{{ route('admin.activation_codes.destroy', $c) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('حذف الكود؟')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">لا توجد أكواد.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $codes->links('vendor.pagination.bootstrap-custom') }}
@endsection
