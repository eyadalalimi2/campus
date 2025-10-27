@extends('admin.layouts.app')

@section('title', 'المحتوى الطبي الخاص')

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">المحتوى الطبي الخاص</h4>
        <a href="{{ route('admin.medical_contents.create') }}" class="btn btn-primary">إضافة محتوى</a>
    </div>

    {{-- فلاتر --}}
    <form method="get" class="card mb-3 p-3">
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="بحث بالعنوان/الوصف">
            </div>

            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">نوع المحتوى</option>
                    <option value="file"  @selected($filters['type']==='file')>ملف</option>
                    <option value="link"  @selected($filters['type']==='link')>رابط</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">حالة النشر</option>
                    <option value="draft"      @selected($filters['status']==='draft')>مسودة</option>
                    <option value="in_review"  @selected($filters['status']==='in_review')>قيد المراجعة</option>
                    <option value="published"  @selected($filters['status']==='published')>منشور</option>
                    <option value="archived"   @selected($filters['status']==='archived')>مؤرشف</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="is_active" class="form-select">
                    <option value="">التفعيل</option>
                    <option value="1" @selected($filters['is_active']==='1')>مفعّل</option>
                    <option value="0" @selected($filters['is_active']==='0')>غير مفعّل</option>
                </select>
            </div>

            <div class="col-12"><hr></div>

            {{-- المسار المؤسسي --}}
            <div class="col-md-3">
                <select name="university_id" id="flt_university" class="form-select">
                    <option value="">الجامعة</option>
                    @foreach($universities as $u)
                        <option value="{{ $u->id }}" @selected($filters['university_id']==$u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="branch_id" id="flt_branch" class="form-select">
                    <option value="">الفرع</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" data-university="{{ $b->university_id }}" @selected($filters['branch_id']==$b->id)>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="college_id" id="flt_college" class="form-select">
                    <option value="">الكلية</option>
                    @foreach($colleges as $c)
                        <option value="{{ $c->id }}" data-branch="{{ $c->branch_id }}" data-university="{{ $c->university_id }}" @selected($filters['college_id']==$c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select name="major_id" id="flt_major" class="form-select">
                    <option value="">التخصص</option>
                    @foreach($majors as $m)
                        <option value="{{ $m->id }}" data-college="{{ $m->college_id }}" @selected($filters['major_id']==$m->id)>{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 text-end">
                <button class="btn btn-secondary">تطبيق الفلتر</button>
            </div>
        </div>
    </form>

    {{-- الجدول --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>النوع</th>
                        <th>الجامعة/الفرع/الكلية/التخصص</th>
                        <th>الحالة</th>
                        <th>تفعيل</th>
                        <th>تاريخ النشر</th>
                        <th class="text-end">عمليات</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($contents as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td class="fw-semibold">{{ $row->title }}</td>
                        <td>
                            @if($row->type === 'file') ملف
                            @elseif($row->type === 'link') رابط
                            @else — @endif
                        </td>
                        <td style="max-width:320px">
                            <div><small class="text-muted">جامعة:</small> {{ $row->university->name ?? '-' }}</div>
                            <div><small class="text-muted">فرع:</small> {{ $row->branch->name ?? '-' }}</div>
                            <div><small class="text-muted">كلية:</small> {{ $row->college->name ?? '-' }}</div>
                            <div><small class="text-muted">تخصص:</small> {{ $row->major->name ?? '-' }}</div>
                        </td>
                        <td>
                            @php
                                $statusMap = ['draft'=>'مسودة','in_review'=>'قيد المراجعة','published'=>'منشور','archived'=>'مؤرشف'];
                            @endphp
                            <span class="badge bg-light text-dark">{{ $statusMap[$row->status] ?? $row->status }}</span>
                        </td>
                        <td>
                            {!! $row->is_active ? '<span class="badge bg-success">نعم</span>' : '<span class="badge bg-secondary">لا</span>' !!}
                        </td>
                        <td>{{ optional($row->published_at)->format('Y-m-d') ?? '-' }}</td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.medical_contents.edit', $row->id) }}">تعديل</a>
                            <form action="{{ route('admin.medical_contents.destroy', $row->id) }}" method="post" class="d-inline"
                                  onsubmit="return confirm('تأكيد الحذف؟');">
                                @csrf @method('delete')
                                <button class="btn btn-sm btn-outline-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">لا توجد بيانات</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $contents->links('vendor.pagination.bootstrap-custom') }}
        </div>
    </div>
</div>

{{-- فلترة متسلسلة في الواجهة بدون مسارات إضافية --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const $u = document.getElementById('flt_university');
    const $b = document.getElementById('flt_branch');
    const $c = document.getElementById('flt_college');
    const $m = document.getElementById('flt_major');

    function filterBranches() {
        const uid = $u.value;
        [...$b.options].forEach((opt, i) => {
            if (i===0) return; // العنوان
            opt.hidden = uid && opt.dataset.university !== uid;
        });
    }
    function filterColleges() {
        const bid = $b.value;
        const uid = $u.value;
        [...$c.options].forEach((opt, i) => {
            if (i===0) return;
            opt.hidden = (uid && opt.dataset.university !== uid) || (bid && opt.dataset.branch !== bid);
        });
    }
    function filterMajors() {
        const cid = $c.value;
        [...$m.options].forEach((opt, i) => {
            if (i===0) return;
            opt.hidden = (cid && opt.dataset.college !== cid);
        });
    }

    $u?.addEventListener('change', () => { filterBranches(); filterColleges(); filterMajors(); });
    $b?.addEventListener('change', () => { filterColleges(); filterMajors(); });
    $c?.addEventListener('change', () => { filterMajors(); });

    filterBranches(); filterColleges(); filterMajors();
});
</script>
@endsection