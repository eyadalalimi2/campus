<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>تعديل كود</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="p-3">
<div class="container">
  <h3 class="mb-3">تعديل كود #{{ $code->id }}</h3>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form method="post" action="{{ route('admin.activation-codes.update',$code->id) }}" class="row g-3">
    @csrf @method('PUT')
    <div class="col-md-6">
      <label class="form-label">code *</label>
      <input type="text" name="code" class="form-control" value="{{ old('code',$code->code) }}" maxlength="64" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">batch_id</label>
      <input type="number" name="batch_id" class="form-control" value="{{ old('batch_id',$code->batch_id) }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">plan_id *</label>
      <input type="number" name="plan_id" class="form-control" value="{{ old('plan_id',$code->plan_id) }}" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">university_id</label>
      <input type="number" name="university_id" class="form-control" value="{{ old('university_id',$code->university_id) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">college_id</label>
      <input type="number" name="college_id" class="form-control" value="{{ old('college_id',$code->college_id) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">major_id</label>
      <input type="number" name="major_id" class="form-control" value="{{ old('major_id',$code->major_id) }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">duration_days *</label>
      <input type="number" name="duration_days" class="form-control" value="{{ old('duration_days',$code->duration_days) }}" min="1" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">start_policy *</label>
      <select name="start_policy" class="form-select" required>
        @foreach(['on_redeem','fixed_start'] as $p)
          <option value="{{ $p }}" @selected(old('start_policy',$code->start_policy)===$p)>{{ $p }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">starts_on</label>
      <input type="date" name="starts_on" class="form-control" value="{{ old('starts_on',$code->starts_on) }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">valid_from</label>
      <input type="datetime-local" name="valid_from" class="form-control" value="{{ old('valid_from', optional($code->valid_from)->format('Y-m-d\TH:i')) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label">valid_until</label>
      <input type="datetime-local" name="valid_until" class="form-control" value="{{ old('valid_until', optional($code->valid_until)->format('Y-m-d\TH:i')) }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">max_redemptions *</label>
      <input type="number" name="max_redemptions" class="form-control" value="{{ old('max_redemptions',$code->max_redemptions) }}" min="1" max="255" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">status *</label>
      <select name="status" class="form-select" required>
        @foreach(['active','redeemed','expired','disabled'] as $st)
          <option value="{{ $st }}" @selected(old('status',$code->status)===$st)>{{ $st }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">created_by_admin_id</label>
      <input type="number" name="created_by_admin_id" class="form-control" value="{{ old('created_by_admin_id',$code->created_by_admin_id) }}">
    </div>

    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary">تحديث</button>
      <a href="{{ route('admin.activation-codes.index') }}" class="btn btn-secondary">رجوع</a>
    </div>
  </form>
</div>
</body>
</html>
