@extends('admin.layouts.app')
@section('title','الموارد')
@section('content')
<h1>الموارد</h1>
<a href="{{ route('medical.resources.create') }}">+ مورد جديد</a>

<form method="get" style="margin:12px 0;padding:8px;border:1px solid #ddd">
  <label>النوع</label>
  <select name="type">
    <option value="">الكل</option>
    @foreach(['YOUTUBE','BOOK','SUMMARY','REFERENCE','QUESTION_BANK'] as $t)
  <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4 mb-0"><i class="bi bi-collection"></i> الموارد</h1>
      <a href="{{ route('medical.resources.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> مورد جديد</a>
  </div>
  <div class="card mb-3">
      <div class="card-body">
          <form method="get" class="row g-2 align-items-end">
              <div class="col-md-2">
                  <label class="form-label">النوع</label>
                  <select name="type" class="form-select">
                      <option value="">الكل</option>
                      @foreach(['YOUTUBE','BOOK','SUMMARY','REFERENCE','QUESTION_BANK'] as $t)
                          <option value="{{ $t }}" {{ request('type')===$t?'selected':'' }}>{{ $t }}</option>
                      @endforeach
                  </select>
              </div>
              <div class="col-md-2">
                  <label class="form-label">المسار</label>
                  <select name="track" class="form-select">
                      <option value="">الكل</option>
                      @foreach(['BASIC','CLINICAL'] as $t)
                          <option value="{{ $t }}" {{ request('track')===$t?'selected':'' }}>{{ $t }}</option>
                      @endforeach
                  </select>
              </div>
              <div class="col-md-3">
                  <label class="form-label">المادة</label>
                  <select name="subject_id" class="form-select">
                      <option value="">الكل</option>
                      @foreach($subjects as $s)
                          <option value="{{ $s->id }}" {{ (string)request('subject_id')===(string)$s->id?'selected':'' }}>
                              {{ $s->code }} — {{ $s->name_ar }}
                          </option>
                      @endforeach
                  </select>
              </div>
              <div class="col-md-3">
                  <label class="form-label">الجهاز</label>
                  <select name="system_id" class="form-select">
                      <option value="">الكل</option>
                      @foreach($systems as $s)
                          <option value="{{ $s->id }}" {{ (string)request('system_id')===(string)$s->id?'selected':'' }}>
                              {{ $s->name_ar }}
                          </option>
                      @endforeach
                  </select>
              </div>
              <div class="col-md-2">
                  <button class="btn btn-outline-secondary w-100"><i class="bi bi-funnel"></i> فلترة</button>
              </div>
          </form>
      </div>
  </div>
  <tr>
    <td>{{ $x->id }}</td>
    <td>{{ $x->title }}</td>
    <td>{{ $x->type }}</td>
    <td>{{ $x->track }}</td>
    <td>{{ optional($x->subject)->name_ar }}</td>
    <td>{{ optional($x->system)->name_ar }}</td>
    <td>{{ optional($x->doctor)->name }}</td>
    <td>{{ $x->status }}</td>
    <td>
      <a href="{{ route('medical.resources.edit',$x) }}">تعديل</a>
      <form action="{{ route('medical.resources.destroy',$x) }}" method="post" style="display:inline">
        @csrf @method('DELETE') <button onclick="return confirm('حذف؟')">حذف</button>
      </form>
    </td>
  </tr>
  @endforeach
</table>
{{ $items->links() }}
@endsection
