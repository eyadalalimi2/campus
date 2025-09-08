<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>استرداد كود</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="p-3">
<div class="container">
  <h3 class="mb-3">استرداد كود</h3>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @error('error') <div class="alert alert-danger">{{ $message }}</div> @enderror
  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form method="post" action="{{ route('admin.activation-codes.redeem.process') }}" class="row g-3">
    @csrf
    <div class="col-md-6">
      <label class="form-label">الكود *</label>
      <input type="text" name="code" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">user_id *</label>
      <input type="number" name="user_id" class="form-control" required>
    </div>
    <div class="col-12">
      <button class="btn btn-success">استرداد</button>
      <a href="{{ route('admin.activation_codes.index') }}" class="btn btn-secondary">رجوع</a>
    </div>
  </form>
</div>
</body>
</html>
