@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1 class="mb-3">الفصول (خاص)</h1>

  <style>
    .term-box{ display:inline-block; min-width:36px; text-align:center; padding:.35rem .5rem; border-radius:.375rem; color:#fff; font-weight:600; }
    .term-box.active{ background:#198754; }
    .term-box.inactive{ background:#dc3545; }
    .term-box:hover{ opacity:.92; }
    .term-box + form { display:inline-block; vertical-align:middle; }
    .year-label{ font-weight:600; margin-right:.5rem; margin-left:.5rem; }
  </style>

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
              <th>السنوات / الفصول</th>
              <th class="text-end">إجراءات</th>
            </tr>
          </thead>
          <tbody>
            @php
              $termsCollection = method_exists($terms, 'getCollection') ? $terms->getCollection() : $terms;
              // group by major_id via the term's year
              $grouped = $termsCollection->groupBy(function($item){ return optional(optional($item->year)->major)->id ?? 'no_major'; });
            @endphp

            @forelse($grouped as $majorId => $group)
              @php $first = $group->first();
                $major = optional($first->year)->major ?? null;
                $college = optional($major)->college;
                $branch = optional($college)->branch;
                $universityName = optional(optional($branch)->university)->name;
                $branchName = optional($branch)->name;
                $majorName = optional($major)->name;
                $parts = array_filter([$universityName, $branchName, $majorName], fn($v)=>filled($v));
                $byYear = $group->groupBy(fn($it)=> optional($it->year)->year_number ?? '—');
              @endphp

              <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="min-width:220px;">
                  <div class="fw-semibold">{{ implode(' - ', $parts) }}</div>
                </td>
                <td>
                  <div class="d-flex flex-wrap align-items-center">
                    @foreach($byYear->sortKeys() as $yearNumber => $termsInYear)
                      <div class="d-flex align-items-center me-3 mb-2">
                        <div class="year-label">سنة {{ $yearNumber }}</div>
                        @foreach($termsInYear->sortBy('term_number') as $t)
                          <div class="me-2 d-inline-flex align-items-center">
                            <a href="{{ route('admin.medical_terms.edit',$t) }}" class="term-box {{ $t->is_active ? 'active' : 'inactive' }} text-decoration-none">{{ $t->term_number }}</a>
                            <form action="{{ route('admin.medical_terms.destroy',$t) }}" method="post" class="d-inline ms-1" onsubmit="return confirm('تأكيد الحذف؟');">
                              @csrf @method('DELETE')
                              <button class="btn btn-sm btn-link text-danger p-0 ms-1" title="حذف"><i class="bi bi-trash"></i></button>
                            </form>
                          </div>
                        @endforeach
                      </div>
                    @endforeach
                  </div>
                </td>
                <td class="text-end">
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.medical_terms.create', ['major_id' => $majorId]) }}">إضافة فصل</a>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="text-center text-muted">لا توجد بيانات</td></tr>
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