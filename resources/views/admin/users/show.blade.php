@extends('admin.layouts.app')
@section('title', 'بيانات الطالب')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">بيانات الطالب</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil"></i>
                تعديل</a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">رجوع</a>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    @php
                        $photoUrl =
                            $user->profile_photo_url ??
                            ($user->profile_photo_path
                                ? asset('storage/' . $user->profile_photo_path)
                                : 'https://obdcodehub.com/storage/images/icon.png');
                    @endphp
                    <img src="{{ $photoUrl }}" class="rounded-circle mb-3"
                        style="width:160px;height:160px;object-fit:cover" alt="Profile">
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <div class="text-muted small">{{ $user->phone ?: '—' }}</div>
                    <div class="text-muted small">{{ $user->email }}</div>
                    <div class="mt-2">
                        <span class="badge bg-secondary">{{ $user->country?->name_ar ?? '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="text-muted small">الرقم الأكاديمي</div>
                            <div class="fw-semibold">{{ $user->student_number ?: '—' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">المستوى</div>
                            <div class="fw-semibold">{{ $user->level ?: '—' }}</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="text-muted small">الجنس</div>
                            <div class="fw-semibold">{{ $user->gender ? ($user->gender === 'male' ? 'ذكر' : 'أنثى') : '—' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">الحالة</div>
                            <div class="fw-semibold">
                                @if ($user->status === 'active')
                                    <span class="badge bg-success">نشط</span>
                                @elseif($user->status === 'suspended')
                                    <span class="badge bg-warning text-dark">موقوف</span>
                                @elseif($user->status === 'graduated')
                                    <span class="badge bg-info text-dark">متخرج</span>
                                @else
                                    <span class="badge bg-secondary">غير محدد</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="text-muted small">الجامعة / الكلية / التخصص</div>
                            <div class="fw-semibold">
                                {{ $user->university->name ?? '—' }}
                                @if ($user->college)
                                    / {{ $user->college->name }}
                                @endif
                                @if ($user->major)
                                    / {{ $user->major->name }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="text-muted small">تاريخ الإنشاء</div>
                            <div class="fw-semibold">{{ optional($user->created_at)->format('Y-m-d H:i') }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small">آخر تحديث</div>
                            <div class="fw-semibold">{{ optional($user->updated_at)->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
