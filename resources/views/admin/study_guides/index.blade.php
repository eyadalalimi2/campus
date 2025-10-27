@extends('admin.layouts.app')
@section('title','إدارة أدلة المذاكرة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">أدلة المذاكرة لطلاب الطب</h4>
  <a href="{{ route('admin.study_guides.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle"></i> إضافة دليل
  </a>
  </div>

  @include('admin.partials.flash')

  <div class="card">
    <div class="table-responsive">
      <table class="table table-striped mb-0">
        <thead>
          <tr>
            <th>#</th>
            <th>العنوان</th>
            <th>رابط يوتيوب</th>
            <th>تاريخ الإضافة</th>
            <th style="width:160px">إجراءات</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $item)
            <tr>
              <td>{{ $item->id }}</td>
              <td>{{ $item->title }}</td>
              <td>
                <a href="{{ $item->youtube_url }}" target="_blank">مشاهدة</a>
              </td>
              <td>{{ $item->created_at?->format('Y-m-d') }}</td>
              <td>
                <a href="{{ route('admin.study_guides.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-pencil-square"></i>
                </a>
                <form action="{{ route('admin.study_guides.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('تأكيد الحذف؟');">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center">لا توجد عناصر بعد.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="card-footer">{{ $items->links('vendor.pagination.bootstrap-custom') }}</div>
  </div>
@endsection
