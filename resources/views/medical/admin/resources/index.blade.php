@extends('admin.layouts.app')
@section('title','الموارد')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><i class="bi bi-collection"></i> الموارد</h1>
    <a href="{{ route('medical.resources.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> مورد جديد</a>
</div>
<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label">النوع</label>
                <select name="type" class="form-select">
                    <option value="">الكل</option>
                    @foreach(['YOUTUBE','BOOK','SUMMARY','REFERENCE','QUESTION_BANK'] as $t)
                        <option value="{{ $t }}" {{ request('type')===$t?'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">المسار</label>
                <select name="track" class="form-select">
                    <option value="">الكل</option>
                    @foreach(['BASIC','CLINICAL'] as $t)
                        <option value="{{ $t }}" {{ request('track')===$t?'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">المادة</label>
                <select name="subject_id" class="form-select">
                    <option value="">الكل</option>
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ (string)request('subject_id')===(string)$s->id?'selected':'' }}>
                            {{ $s->code }} — {{ $s->name_ar }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">الجهاز</label>
                <select name="system_id" class="form-select">
                    <option value="">الكل</option>
                    @foreach($systems as $s)
                        <option value="{{ $s->id }}" {{ (string)request('system_id')===(string)$s->id?'selected':'' }}>
                            {{ $s->name_ar }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100"><i class="bi bi-funnel"></i> فلترة</button>
            </div>
        </form>
    </div>
</div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>النوع</th>
                        <th>المسار</th>
                        <th>المادة</th>
                        <th>الجهاز</th>
                        <th>الدكتور</th>
                        <th>الحالة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $x)
                    <tr>
                        <td>{{ $x->id }}</td>
                        <td>{{ $x->title }}</td>
                        <td>{{ $x->type }}</td>
                        <td>{{ $x->track }}</td>
                        <td>{{ optional($x->subject)->name_ar }}</td>
                        <td>{{ optional($x->system)->name_ar }}</td>
                        <td>{{ optional($x->doctor)->name }}</td>
                        <td>{{ $x->status }}</td>
                        <td>
                            <a href="{{ route('medical.resources.edit',$x) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> تعديل</a>
                            <form action="{{ route('medical.resources.destroy',$x) }}" method="post" class="d-inline">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')"><i class="bi bi-trash"></i> حذف</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="text-center">لا توجد موارد</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $items->links('vendor.pagination.bootstrap-custom') }}</div>
    </div>
@endsection
