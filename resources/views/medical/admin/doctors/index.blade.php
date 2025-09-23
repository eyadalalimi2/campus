
@extends('admin.layouts.app')
@section('title','الدكاترة')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0"><i class="bi bi-person-badge"></i> الدكاترة</h1>
  <a href="{{ route('medical.doctors.create') }}" class="btn btn-primary"><i class="bi bi-plus"></i> دكتور جديد</a>
</div>
<div class="card">
  <div class="table-responsive">
    <table class="table table-bordered align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>الاسم</th>
          <th>القناة</th>
          <th>الدولة</th>
          <th>معتمد</th>
          <th>Score</th>
          <th>إجراءات</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $x)
        <tr>
          <td>{{ $x->name }}</td>
          <td><a href="{{ $x->channel_url }}" target="_blank"><i class="bi bi-box-arrow-up-right"></i> قناة</a></td>
          <td>{{ $x->country }}</td>
          <td>{!! $x->verified ? '<span class="text-success"><i class="bi bi-check-circle"></i></span>' : '<span class="text-danger"><i class="bi bi-x-circle"></i></span>' !!}</td>
          <td>{{ $x->score }}</td>
          <td>
            <a href="{{ route('medical.doctors.edit',$x) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> تعديل</a>
            <form action="{{ route('medical.doctors.destroy',$x) }}" method="post" class="d-inline">@csrf @method('DELETE')
              <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')"><i class="bi bi-trash"></i> حذف</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center">لا يوجد دكاترة</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer">{{ $items->links('vendor.pagination.bootstrap-custom') }}</div>
</div>
@endsection
