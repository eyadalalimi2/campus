@extends('admin.layouts.app')
@section('title', 'دفعات الأكواد')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">دفعات الأكواد</h4>
        <a href="{{ route('admin.activation_code_batches.create') }}" class="btn btn-primary">
            <i class="bi bi-plus"></i> إنشاء دفعة
        </a>
    </div>

    <form class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="بحث بالاسم">
        </div>
        <div class="col-md-3">
            <select name="plan_id" class="form-select" onchange="this.form.submit()">
                <option value="">— كل الخطط —</option>
                @foreach ($plans as $p)
                    <option value="{{ $p->id }}" @selected(request('plan_id') == $p->id)>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="university_id" class="form-select" onchange="this.form.submit()">
                <option value="">— كل الجامعات —</option>
                @foreach ($universities as $u)
                    <option value="{{ $u->id }}" @selected(request('university_id') == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">— الحالة —</option>
                <option value="draft" @selected(request('status') === 'draft')>مسودة</option>
                <option value="active" @selected(request('status') === 'active')>مفعّلة</option>
                <option value="disabled" @selected(request('status') === 'disabled')>موقوفة</option>
                <option value="archived" @selected(request('status') === 'archived')>مؤرشفة</option>
            </select>
        </div>
        <div class="col-md-1">
            <button class="btn btn-outline-secondary w-100">بحث</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover bg-white align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>الخطة</th>
                    <th>الجامعة/الكلية/التخصص</th>
                    <th>الكمية</th>
                    <th>الحالة</th>
                    <th>أكواد مُنشأة</th>
                    <th class="text-center">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($batches as $batch)
                    <tr>
                        <td class="text-muted">{{ $batch->id }}</td>
                        <td class="fw-semibold">{{ $batch->name }}</td>
                        <td>#{{ $batch->plan_id }}</td>
                        <td class="small text-muted">
                            {{ $batch->university->name ?? '—' }}
                            @if ($batch->college) / {{ $batch->college->name }}
                            @endif
                            @if ($batch->major) / {{ $batch->major->name }}
                            
                @endif
                </td>
                <td>{{ $batch->quantity }}</td>
                <td>
                    @php $label = $batch->status_label; @endphp
                    <span
                        class="badge
            @switch($batch->status)
              @case('active')   bg-success @break
              @case('disabled') bg-secondary @break
              @case('archived') bg-dark @break
              @default          bg-info text-dark
            @endswitch
          ">{{ $label }}</span>
                </td>
                <td>{{ $batch->activation_codes_count }}</td>
                <td class="text-center">
                    <a href="{{ route('admin.activation_code_batches.show', $batch) }}"
                        class="btn btn-sm btn-outline-secondary">
                        عرض
                    </a>
                    <a href="{{ route('admin.activation_code_batches.edit', $batch) }}"
                        class="btn btn-sm btn-outline-primary">
                        تعديل
                    </a>
                    <a href="{{ route('admin.activation_code_batches.export', $batch) }}"
                        class="btn btn-sm btn-outline-success">
                        تصدير
                    </a>
                    <form action="{{ route('admin.activation_code_batches.destroy', $batch) }}" method="POST"
                        class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"
                            onclick="return confirm('حذف الدفعة؟ سيتم إبقاء الأكواد لكن بدون ربط الدفعة.')">حذف</button>
                    </form>
                </td>
                </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">لا توجد دفعات.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $batches->links('vendor.pagination.bootstrap-custom') }}
    @endsection
