@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
  <h1 class="mb-3">ربط الأنظمة بمواد المسار SYSTEM</h1>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <div class="card mb-3">
    <div class="card-body">
      <form method="get" class="row g-2">
        <div class="col-md-8">
          <label class="form-label">اختر النظام</label>
          <select name="system_id" class="form-select" onchange="this.form.submit()">
            <option value="">-- اختر --</option>
            @foreach($systems as $s)
              <option value="{{ $s->id }}" @selected($systemId==$s->id)>
                #{{ $s->id }} - {{ optional($s->year->major)->name }} / سنة {{ optional($s->year)->year_number }} / ترم {{ optional($s->term)->term_number }} - {{ $s->display_name ?? optional($s->device)->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
          <button class="btn btn-outline-secondary w-100" type="submit">تحديث</button>
        </div>
      </form>
    </div>
  </div>

  @if($systemId)
    <div class="card mb-3">
      <div class="card-header">الروابط الحالية</div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-striped table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>المادة العامة</th>
                <th>السنة/الترم</th>
                <th>المسار</th>
                <th class="text-end">إجراءات</th>
              </tr>
            </thead>
            <tbody>
              @forelse($links as $lnk)
                <tr>
                  <td>{{ $lnk->id }}</td>
                  <td>{{ optional($lnk->subject->medSubject)->name }}</td>
                  <td>
                    سنة {{ optional($lnk->subject->term->year)->year_number }}
                    / ترم {{ optional($lnk->subject->term)->term_number }}
                  </td>
                  <td>{{ $lnk->subject->track }}</td>
                  <td class="text-end">
                    <form method="post" action="{{ route('admin.medical_system_subjects.destroy',$lnk) }}" onsubmit="return confirm('حذف الربط؟')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger">حذف</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center text-muted">لا توجد روابط</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">إضافة ربط جديد</div>
      <div class="card-body">
        <form method="post" action="{{ route('admin.medical_system_subjects.store') }}" class="row g-3">
          @csrf
          <input type="hidden" name="system_id" value="{{ $systemId }}">
          <div class="col-md-6">
            <label class="form-label">ID المادة الخاصة (SYSTEM لنفس السنة)</label>
            <input name="subject_id" class="form-control" placeholder="أدخل ID للمادة">
            @error('subject_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="col-12">
            <button class="btn btn-primary">إضافة</button>
          </div>
        </form>
      </div>
    </div>
  @endif
</div>
@endsection