@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1 class="mb-3">مواد الطب (خاص)</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="mb-3">
    <a href="{{ route('admin.medical_subjects.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> إضافة مادة
    </a>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>الاسم الظاهر</th>
              <th>المادة العامة</th>
              <th>الصورة</th>
              <th>المسار</th>
              <th>السنة/الترم</th>
              <th>مفعل</th>
              <th>الترتيب</th>
              <th class="text-end">إجراءات</th>
            </tr>
          </thead>
          <tbody>
            @forelse($subjects as $s)
              <tr>
                <td>{{ $s->id }}</td>
                <td>{{ $s->display_name ?? optional($s->medSubject)->name }}</td>
                <td>{{ optional($s->medSubject)->name }}</td>
                <td>
                  @php
                    $img = $s->image ?: (optional($s->medSubject)->image_path ?? null);
                  @endphp
                  @if($img)
                    <img src="{{ asset('storage/' . $img) }}" alt="صورة المادة" style="max-width:60px;max-height:60px;">
                  @else
                    <span class="text-muted">لا يوجد</span>
                  @endif
                </td>
                <td>{{ $s->track }}</td>
                <td>{{ optional($s->term->year)->year_number }} / {{ optional($s->term)->term_number }}</td>
                <td>{!! $s->is_active ? '<span class="badge bg-success">مفعل</span>' : '<span class="badge bg-secondary">معطل</span>' !!}</td>
                <td>{{ $s->sort_order }}</td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.medical_subjects.edit',$s) }}">تعديل</a>
                  <form action="{{ route('admin.medical_subjects.destroy',$s) }}" method="post" class="d-inline" onsubmit="return confirm('تأكيد الحذف؟');">
                    @csrf @method('DELETE')
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
    </div>
    @if(method_exists($subjects,'links'))
      <div class="card-footer">
        {{ $subjects->links('vendor.pagination.bootstrap-custom') }}
      </div>
    @endif
  </div>
</div>
@endsection