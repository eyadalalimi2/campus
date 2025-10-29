@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">نصائح طبية</h3>
        <a href="{{ route('admin.medical_tips.create') }}" class="btn btn-primary">إضافة نصيحة جديدة</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th style="width:60px;">المعرف</th>
                    <th style="width:80px;">الترتيب</th>
                    <th style="width:90px;">غلاف</th>
                    <th>العنوان</th>
                    <th style="width:240px;">الوصف المختصر</th>
                    <th style="width:180px;" class="text-nowrap">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tips as $tip)
                    <tr>
                        <td>{{ $tip->id }}</td>
                        <td>{{ $tip->order }}</td>
                        <td>
                            @if($tip->cover)
                                <img src="{{ asset('storage/' . $tip->cover) }}" alt="cover" style="width:72px;height:48px;object-fit:cover;border-radius:4px;" />
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $tip->title }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($tip->short_description, 80) }}</td>
                        <td class="text-nowrap">
                            <div class="d-inline-flex gap-1">
                                <a href="{{ route('admin.medical_tips.edit', $tip) }}" class="btn btn-sm btn-secondary">تعديل</a>
                                <form action="{{ route('admin.medical_tips.destroy', $tip) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">حذف</button>
                                </form>
                                <a href="{{ $tip->youtube_url }}" target="_blank" class="btn btn-sm btn-success">عرض الفيديو</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $tips->links() }}
    </div>
@endsection
