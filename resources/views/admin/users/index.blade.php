@extends('admin.layouts.app')
@section('title', 'إدارة الطلاب')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">الطلاب</h4>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> طالب جديد</a>
    </div>

    <form class="row g-2 mb-3">
        <div class="col-md-3">
            <input type="text" name="q" class="form-control" value="{{ request('q') }}"
                placeholder="بحث: الاسم/الإيميل/الرقم الأكاديمي">
        </div>
        <div class="col-md-2">
            <select name="university_id" class="form-select" onchange="this.form.submit()">
                <option value="">— الجامعة —</option>
                @foreach ($universities as $u)
                    <option value="{{ $u->id }}" @selected(request('university_id') == $u->id)>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="college_id" class="form-select" onchange="this.form.submit()">
                <option value="">— الكلية —</option>
                @foreach ($colleges as $c)
                    <option value="{{ $c->id }}" @selected(request('college_id') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="major_id" class="form-select" onchange="this.form.submit()">
                <option value="">— التخصص —</option>
                @foreach ($majors as $m)
                    <option value="{{ $m->id }}" @selected(request('major_id') == $m->id)>{{ $m->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select name="country" class="form-select" onchange="this.form.submit()">
                <option value="">— كل الدول —</option>
                @php
                    $arabCountries = [
                        'اليمن',
                        'السعودية',
                        'الإمارات',
                        'قطر',
                        'الكويت',
                        'البحرين',
                        'عُمان',
                        'مصر',
                        'الأردن',
                        'فلسطين',
                        'سوريا',
                        'لبنان',
                        'العراق',
                        'الجزائر',
                        'المغرب',
                        'تونس',
                        'ليبيا',
                        'السودان',
                        'موريتانيا',
                    ];
                @endphp
                @foreach ($arabCountries as $country)
                    <option value="{{ $country }}" @selected(request('country') == $country)>
                        {{ $country }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-1">
            <input type="number" name="level" class="form-control" value="{{ request('level') }}" placeholder="مستوى">
        </div>
        <div class="col-md-2">
            <select name="gender" class="form-select" onchange="this.form.submit()">
                <option value="">— الجنس —</option>
                <option value="male" @selected(request('gender') === 'male')>ذكر</option>
                <option value="female" @selected(request('gender') === 'female')>أنثى</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">— الحالة —</option>
                <option value="active" @selected(request('status') === 'active')>نشط</option>
                <option value="suspended" @selected(request('status') === 'suspended')>موقوف</option>
                <option value="graduated" @selected(request('status') === 'graduated')>متخرج</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-outline-secondary w-100">بحث</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>البروفيل</th>
                    <th>أسم الطالب</th>
                    <th>رقم الهاتف</th>
                    <th>الرقم الأكاديمي</th>
                    <th>الجامعة / الكلية / التخصص</th>
                    <th>المستوى</th>
                    <th>الجنس</th>
                    <th>الدولة</th>
                    <th>الحالة</th>
                    <th class="text-center">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                    <tr>
                        <td>
                            @if ($u->profile_photo_url)
                                <img src="{{ $u->profile_photo_url }}" class="rounded-circle"
                                    style="width:40px;height:40px;object-fit:cover">
                            @else
                                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                                    style="width:40px;height:40px;">
                                    <i class="bi bi-person text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $u->name }}</td>
                        <td>{{ $u->phone ?: '—' }}</td>
                        <td>{{ $u->student_number ?: '—' }}</td>
                        <td class="small text-muted">
                            {{ $u->university->name ?? '—' }}
                            @if ($u->college)
                                / {{ $u->college->name }}
                            @endif
                            @if ($u->major)
                                / {{ $u->major->name }}
                            @endif
                        </td>
                        <td>{{ $u->level ?: '—' }}</td>
                        <td>
                            @if ($u->gender === 'male')
                                ذكر
                            @elseif($u->gender === 'female')
                                أنثى
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $u->country ?: '—' }}</td>
                        <td>
                            @if ($u->status === 'active')
                                <span class="badge bg-success">نشط</span>
                            @elseif($u->status === 'suspended')
                                <span class="badge bg-warning text-dark">موقوف</span>
                            @else
                                <span class="badge bg-secondary">متخرج</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.users.show', $u) }}" class="btn btn-sm btn-outline-secondary">عرض</a>
                            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-primary">تعديل</a>
                            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('حذف الطالب؟')">حذف</button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">لا توجد بيانات.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $users->links('vendor.pagination.bootstrap-custom') }}
@endsection
