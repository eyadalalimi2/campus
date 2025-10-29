@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>فيديوهات: {{ $activity_button->title }}</h3>
            <a href="{{ route('admin.activity_buttons.videos.create', $activity_button->id) }}" class="btn btn-primary">إضافة فيديو جديد</a>
        </div>

        <div class="card shadow-sm border-0 bg-white">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr class="table-light text-muted">
                            <th class="px-3 py-2 text-center" style="width:6%">المعرف</th>
                            <th class="px-3 py-2" style="width:22%">غلاف</th>
                            <th class="px-3 py-2" style="width:58%">العنوان</th>
                            <th class="px-3 py-2 text-center" style="width:10%">الترتيب</th>
                            <th class="px-3 py-2 text-center" style="width:10%">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($videos as $v)
                            <tr>
                                <td class="px-3 py-2 align-middle text-center text-muted">{{ $v->id }}</td>
                                <td class="px-3 py-2 align-middle">
                                    @if($v->cover_image)
                                        <img src="{{ asset('storage/'.$v->cover_image) }}" style="width:120px; height:auto; border-radius:6px;" />
                                    @else
                                        <div class="bg-light text-center" style="width:120px;height:70px;display:flex;align-items:center;justify-content:center;border-radius:6px;">لا يوجد</div>
                                    @endif
                                </td>
                                <td class="px-3 py-2 align-middle text-dark">{{ $v->title }}</td>
                                <td class="px-3 py-2 align-middle text-center text-muted">{{ $v->order }}</td>
                                <td class="px-3 py-2 align-middle text-center text-nowrap">
                                    <div class="d-inline-flex align-items-center gap-1">
                                        {{-- زر عرض الفيديو (يفتح رابط اليوتيوب في تبويب جديد) --}}
                                        <a href="{{ $v->youtube_url }}" class="btn btn-sm btn-success text-white" target="_blank">عرض الفيديو</a>
                                        <a href="{{ route('admin.activity_videos.edit', $v->id) }}" class="btn btn-sm btn-secondary text-white">تعديل</a>
                                        <form action="{{ route('admin.activity_videos.destroy', $v->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('هل متأكد من حذف هذا الفيديو؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger text-white">حذف</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
