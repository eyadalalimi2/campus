<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>دفعات الأكواد</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="p-3">
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>دفعات الأكواد</h3>
    <a href="{{ route('admin.activation-code-batches.create') }}" class="btn btn-primary">إنشاء دفعة</a>
  </div>

  <form class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" name="s" value="{{ request('s') }}" class="form-control" placeholder="بحث بالاسم/الوصف">
    </div>
    <div class="col-md-3">
      <select name="status" class="form-select">
        <option value="">الحالة</option>
        @foreach(['draft','active','disabled','archived'] as $st)
          <option value="{{ $st }}" @selected(request('status')===$st)>{{ $st }}</option>
        @endforeach
      </select>
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
        <th>#</th><th>الاسم</th><th>الخطة</th><th>الكمية</th><th>الحالة</th><th>أوامر</th>
      </tr></thead>
      <tbody>
      @foreach($batches as $b)
        <tr>
          <td>{{ $b->id }}</td>
          <td><a href="{{ route('admin.activation-code-batches.show',$b->id) }}">{{ $b->name }}</a></td>
          <td>{{ $b->plan_id }}</td>
          <td>{{ $b->quantity }}</td>
          <td>{{ $b->status }}</td>
          <td class="d-flex gap-2">
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.activation-code-batches.edit',$b->id) }}">تعديل</a>
            <form method="post" action="{{ route('admin.activation-code-batches.destroy',$b->id) }}" onsubmit="return confirm('حذف؟');">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">حذف</button>
            </form>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  {{ $batches->withQueryString()->links() }}
</div>
</body>
</html>
