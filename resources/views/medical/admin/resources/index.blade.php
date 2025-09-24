@extends('admin.layouts.app')
@section('title','الموارد')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h4 mb-0"><i class="bi bi-collection"></i> إدارة الموارد التعليمية</h1>
    <a href="{{ route('medical.resources.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> إضافة مورد جديد</a>
</div>
<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label">نوع المورد</label>
                <select name="type" class="form-select">
                    <option value="">الكل</option>
                    <option value="YOUTUBE" {{ request('type')==='YOUTUBE'?'selected':'' }}>يوتيوب</option>
                    <option value="BOOK" {{ request('type')==='BOOK'?'selected':'' }}>كتاب</option>
                    <option value="SUMMARY" {{ request('type')==='SUMMARY'?'selected':'' }}>ملخص</option>
                    <option value="REFERENCE" {{ request('type')==='REFERENCE'?'selected':'' }}>مرجع علمي</option>
                    <option value="QUESTION_BANK" {{ request('type')==='QUESTION_BANK'?'selected':'' }}>بنك أسئلة</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">المسار الدراسي</label>
                <select name="track" class="form-select">
                    <option value="">الكل</option>
                    <option value="BASIC" {{ request('track')==='BASIC'?'selected':'' }}>أساسي</option>
                    <option value="CLINICAL" {{ request('track')==='CLINICAL'?'selected':'' }}>إكلينيكي</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">المادة الدراسية</label>
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
                <label class="form-label">الجهاز/التخصص</label>
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
                <button class="btn btn-outline-secondary w-100"><i class="bi bi-funnel"></i> تطبيق الفلاتر</button>
            </div>
        </form>
    </div>
</div>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>الرقم</th>
                        <th>العنوان</th>
                        <th>نوع المورد</th>
                        <th>المسار الدراسي</th>
                        <th>المادة الدراسية</th>
                        <th>الجهاز/التخصص</th>
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
                        <td>
                          @if($x->type==='YOUTUBE') يوتيوب
                          @elseif($x->type==='BOOK') كتاب
                          @elseif($x->type==='SUMMARY') ملخص
                          @elseif($x->type==='REFERENCE') مرجع علمي
                          @elseif($x->type==='QUESTION_BANK') بنك أسئلة
                          @else {{ $x->type }}
                          @endif
                        </td>
                        <td>@if($x->track==='BASIC') أساسي @elseif($x->track==='CLINICAL') إكلينيكي @else {{ $x->track }} @endif</td>
                        <td>{{ optional($x->subject)->name_ar }}</td>
                        <td>{{ optional($x->system)->name_ar }}</td>
                        <td>{{ optional($x->doctor)->name }}</td>
                        <td>
                          @if($x->status==='PUBLISHED') <span class="badge bg-success">منشور</span>
                          @elseif($x->status==='DRAFT') <span class="badge bg-warning text-dark">مسودة</span>
                          @elseif($x->status==='ARCHIVED') <span class="badge bg-secondary">مؤرشف</span>
                          @else {{ $x->status }}
                          @endif
                        </td>
                        <td>
                            <a href="{{ route('medical.resources.show',$x) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> عرض</a>
                            <a href="{{ route('medical.resources.edit',$x) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> تعديل</a>
                            <form action="{{ route('medical.resources.destroy',$x) }}" method="post" class="d-inline">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')"><i class="bi bi-trash"></i> حذف</button>
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
