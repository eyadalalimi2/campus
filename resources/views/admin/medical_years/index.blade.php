@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1 class="mb-3">سنوات الطب (خاص)</h1>

  <style>
    .year-box{ display:inline-block; min-width:36px; text-align:center; padding:.35rem .5rem; border-radius:.375rem; color:#fff; font-weight:600; }
    .year-box.active{ background:#198754; } /* bootstrap success */
    .year-box.inactive{ background:#dc3545; } /* bootstrap danger */
    .year-box:hover{ opacity:.92; }
    .year-box + form { display:inline-block; vertical-align:middle; }
    .year-box .year-thumb{ height:20px; width:20px; object-fit:cover; border-radius:4px; background:#fff; vertical-align:middle; margin-inline-end:6px; }
  </style>

 

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="mb-3">
    <a href="{{ route('admin.medical_years.create') }}" class="btn btn-primary">
      <i class="bi bi-plus-circle"></i> إضافة سنة
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
              <th>السنوات</th>
              <th class="text-end">إجراءات</th>
            </tr>
          </thead>
          <tbody>
            @php
              // If $years is a paginator, getCollection(); otherwise assume it's a collection
              $yearsCollection = method_exists($years, 'getCollection') ? $years->getCollection() : $years;
              $grouped = $yearsCollection->groupBy('major_id');
            @endphp

            @forelse($grouped as $majorId => $group)
              @php $first = $group->first();
                $major = $first->major ?? null;
                $college = optional($major)->college;
                $branch = optional($college)->branch;
                $universityName = optional(optional($branch)->university)->name;
                $branchName = optional($branch)->name;
                $majorName = optional($major)->name;
                $parts = array_filter([$universityName, $branchName, $majorName], fn($v) => filled($v));
              @endphp
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="min-width:220px;">
                  <div class="fw-semibold">{{ implode(' - ', $parts) }}</div>
                </td>
                <td>
                  <div class="d-flex flex-wrap align-items-center">
                    @foreach($group->sortBy('year_number') as $y)
                      <div class="me-2 mb-2 d-inline-flex align-items-center">
                        <a href="{{ route('admin.medical_years.edit',$y) }}" class="year-box {{ $y->is_active ? 'active' : 'inactive' }} text-decoration-none">
                          @if(!empty($y->image_url))
                            <img src="{{ $y->image_url }}" alt="" class="year-thumb">
                          @endif
                          {{ $y->year_number }}
                        </a>
                        <form action="{{ route('admin.medical_years.destroy',$y) }}" method="post" class="d-inline ms-1" onsubmit="return confirm('تأكيد الحذف؟');">
                          @csrf @method('DELETE')
                          <button class="btn btn-sm btn-link text-danger p-0 ms-1" title="حذف"><i class="bi bi-trash"></i></button>
                        </form>
                      </div>
                    @endforeach
                  </div>
                </td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.medical_years.create', ['major_id' => $majorId]) }}">
                    إضافة سنة
                  </a>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center text-muted">لا توجد بيانات</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if(method_exists($years,'links'))
      <div class="card-footer">
        {{ $years->links('vendor.pagination.bootstrap-custom') }}
      </div>
    @endif
  </div>
</div>
@endsection