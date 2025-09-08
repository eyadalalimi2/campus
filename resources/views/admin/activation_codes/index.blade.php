<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>الأكواد</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="p-3">
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>الأكواد</h3>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.activation-codes.create') }}" class="btn btn-primary">إنشاء يدوي</a>
      <a href="{{ route('admin.activation-codes.redeem') }}" class="btn btn-outline-success">استرداد كود</a>
    </div>
  </div>

  <form class="row g-2 mb-3">
    <div class="col-md-3">
      <input type="text" name="s" value="{{ request('s') }}" class="form-control" placeholder="بحث بالكود">
    </div>
    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">الحالة</option>
        @foreach(['active','redeemed','expired','disabled'] as $st)
          <option value="{{ $st }}" @selected(request('status')===$st)>{{ $st }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <input type="number" name="batch_id" class="form-control" value="{{ request('batch_id') }}" placeholder="batch_id">
    </div>
    <div class="col-md-2">
      <button class="btn btn-secondary w-100">تصفية</button>
    </div>
  </form>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @error('error') <div class="alert alert-danger">{{ $message }}</div> @enderror

  <div class="table-responsive">
    <table class="table table-striped">
      <thead><tr>
        <th>#</th><th>الكود</th><th>batch</th><th>plan</th><th>الحالة</th><th>استرداد</th><th>أوامر</th>
      </tr></thead>
      <tbody>
      @foreach($codes as $c)
        <tr>
          <td>{{ $c->id }}</td>
          <td><code>{{ $c->code }}</code></td>
          <td>{{ $c->batch_id }}</td>
          <td>{{ $c->plan_id }}</td>
          <td>{{ $c->status }}</td>
          <td>
            @if($c->redeemed_at)
              {{ $c->redeemed_at }} (user_id={{ $c->redeemed_by_user_id }})
            @else
              —
            @endif
          </td>
          <td class="d-flex gap-2">
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.activation-codes.edit',$c->id) }}">تعديل</a>
            <form method="post" action="{{ route('admin.activation-codes.destroy',$c->id) }}" onsubmit="return confirm('حذف؟');">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">حذف</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  {{ $codes->withQueryString()->links() }}
</div>
</body>
</html>
