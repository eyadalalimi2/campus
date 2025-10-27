@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1 class="mb-3">الفصول (خاص)</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="mb-3">
    <a href="{{ route('admin.medical_terms.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> إضافة فصل
    </a>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-striped table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>الجامعة - الفرع - التخصص</th>
              <th>السنة</th>
              <th>رقم الترم</th>
              <th>مفعل</th>
              <th>الترتيب</th>
              <th class="text-end">إجراءات</th>
            </tr>
          </thead>
          <tbody>
            @forelse($terms as $t)
              <tr>
                <td>{{ $t->id }}</td>
                <td>
                  @php
                    $major = optional($t->year)->major;
                    $college = optional($major)->college;
                    $branch = optional($college)->branch;
                    $universityName = optional(optional($branch)->university)->name;
                    $branchName = optional($branch)->name;
                    $majorName = optional($major)->name;
                    $parts = array_filter([$universityName, $branchName, $majorName], fn($v)=>filled($v));
                  @endphp
                  {{ implode(' - ', $parts) }}
                </td>
                <td>{{ optional($t->year)->year_number }}</td>
                <td>{{ $t->term_number }}</td>
                <td>{!! $t->is_active ? '<span class="badge bg-success">مفعل</span>' : '<span class="badge bg-secondary">معطل</span>' !!}</td>
                <td>{{ $t->sort_order }}</td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.medical_terms.edit',$t) }}">تعديل</a>
                  <form action="{{ route('admin.medical_terms.destroy',$t) }}" method="post" class="d-inline" onsubmit="return confirm('تأكيد الحذف؟');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">حذف</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr><td colspan="7" class="text-center text-muted">لا توجد بيانات</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if(method_exists($terms,'links'))
      <div class="card-footer">
        {{ $terms->links('vendor.pagination.bootstrap-custom') }}
      </div>
    @endif
  </div>
</div>
@endsection