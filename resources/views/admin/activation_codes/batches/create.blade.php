<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>إنشاء دفعة</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="p-3">
<div class="container">
  <h3 class="mb-3">إنشاء دفعة أكواد</h3>

  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form method="post" action="{{ route('admin.activation-code-batches.store') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
      <label class="form-label">الاسم *</label>
      <input type="text" name="name" class="form-control" value="{{ old('name') }}" required maxlength="150">
    </div>
    <div class="col-md-6">
      <label class="form-label">الخطة (plan_id) *</label>
      <input type="number" name="plan_id" class="form-control" value="{{ old('plan_id') }}" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">university_id</label>
      <input type="number" name="university_id" class="form-control" value="{{ old('university_id') }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">college_id</label>
      <input type="number" name="college_id" class="form-control" value="{{ old('college_id') }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">major_id</label>
      <input type="number" name="major_id" class="form-control" value="{{ old('major_id') }}">
    </div>

    <div class="col-md-3">
      <label class="form-label">الكمية *</label>
      <input type="number" name="quantity" class="form-control" value="{{ old('quantity',1) }}" min="1" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">الحالة *</label>
      <select name="status" class="form-select" required>
        @foreach(['draft','active','disabled','archived'] as $st)
          <option value="{{ $st }}" @selected(old('status','draft')===$st)>{{ $st }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">مدة الأيام *</label>
      <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days',365) }}" min="1" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">سياسة البدء *</label>
      <select name="start_policy" class="form-select" required>
        @foreach(['on_redeem','fixed_start'] as $p)
          <option value="{{ $p }}" @selected(old('start_policy','on_redeem')===$p)>{{ $p }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">starts_on (للـ fixed_start)</label>
      <input type="date" name="starts_on" class="form-control" value="{{ old('starts_on') }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">valid_from</label>
      <input type="datetime-local" name="valid_from" class="form-control" value="{{ old('valid_from') }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">valid_until</label>
      <input type="datetime-local" name="valid_until" class="form-control" value="{{ old('valid_until') }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">code_prefix</label>
      <input type="text" name="code_prefix" class="form-control" value="{{ old('code_prefix') }}" maxlength="24">
    </div>
    <div class="col-md-4">
      <label class="form-label">code_length *</label>
      <input type="number" name="code_length" class="form-control" value="{{ old('code_length',14) }}" min="6" max="64" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">created_by_admin_id</label>
      <input type="number" name="created_by_admin_id" class="form-control" value="{{ old('created_by_admin_id') }}">
    </div>

    <div class="col-12">
      <label class="form-label">ملاحظات</label>
      <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
    </div>

    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary">حفظ</button>
      <a href="{{ route('admin.activation-code-batches.index') }}" class="btn btn-secondary">رجوع</a>
    </div>
  </form>
</div>
</body>
</html>
