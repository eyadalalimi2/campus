@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1 class="mb-3">الأنظمة (خاص)</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="mb-3">
    <a href="{{ route('admin.medical_systems.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> إضافة نظام
    </a>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>التخصص</th>
              <th>السنة</th>
              <th>الترم</th>
              <th>الجهاز العام</th>
              <th>الاسم الظاهر</th>
              <th>مفعل</th>
              <th>الترتيب</th>
              <th class="text-end">إجراءات</th>
            </tr>
          </thead>
          <tbody>
            @forelse($systems as $sys)
              <tr>
                <td>{{ $sys->id }}</td>
                <td>{{ optional($sys->year->major)->name }}</td>
                <td>{{ optional($sys->year)->year_number }}</td>
                <td>{{ optional($sys->term)->term_number }}</td>
                <td>{{ optional($sys->device)->name }}</td>
                <td>{{ $sys->display_name }}</td>
                <td>{!! $sys->is_active ? '<span class="badge bg-success">مفعل</span>' : '<span class="badge bg-secondary">معطل</span>' !!}</td>
                <td>{{ $sys->sort_order }}</td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.medical_systems.edit',$sys) }}">تعديل</a>
                  <form action="{{ route('admin.medical_systems.destroy',$sys) }}" method="post" class="d-inline" onsubmit="return confirm('تأكيد الحذف؟');">
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
    @if(method_exists($systems,'links'))
      <div class="card-footer">
        {{ $systems->links() }}
      </div>
    @endif
  </div>
</div>
@endsection