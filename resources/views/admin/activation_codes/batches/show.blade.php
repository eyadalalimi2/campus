<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>تفاصيل الدفعة</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="p-3">
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>دفعة #{{ $batch->id }} - {{ $batch->name }}</h3>
    <div class="d-flex gap-2">
      <form method="post" action="{{ route('admin.activation-code-batches.generate',$batch->id) }}">
        @csrf
        <button class="btn btn-success" onclick="return confirm('توليد الأكواد الآن؟');">توليد الأكواد ({{ $batch->quantity }})</button>
      </form>
      <a class="btn btn-outline-primary" href="{{ route('admin.activation-code-batches.edit',$batch->id) }}">تعديل</a>
    </div>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @error('error') <div class="alert alert-danger">{{ $message }}</div> @enderror

  <div class="row gy-2">
    <div class="col-md-3"><div class="card p-3"><strong>الحالة:</strong> {{ $batch->status }}</div></div>
    <div class="col-md-3"><div class="card p-3"><strong>الكمية:</strong> {{ $batch->quantity }}</div></div>
    <div class="col-md-3"><div class="card p-3"><strong>الأكواد المُنشأة:</strong> {{ $batch->activation_codes_count }}</div></div>
    <div class="col-md-3"><div class="card p-3"><strong>plan_id:</strong> {{ $batch->plan_id }}</div></div>
  </div>

  <div class="mt-4 d-flex gap-2">
    <form method="post" action="{{ route('admin.activation-code-batches.activate',$batch->id) }}">@csrf
      <button class="btn btn-outline-success">تفعيل</button></form>
    <form method="post" action="{{ route('admin.activation-code-batches.disable',$batch->id) }}">@csrf
      <button class="btn btn-outline-warning">تعطيل</button></form>
    <form method="post" action="{{ route('admin.activation-code-batches.archive',$batch->id) }}">@csrf
      <button class="btn btn-outline-secondary">أرشفة</button></form>
  </div>

  <hr class="my-4">

  <a href="{{ route('admin.activation-codes.index',['batch_id'=>$batch->id]) }}" class="btn btn-primary">
    عرض الأكواد التابعة لهذه الدفعة
  </a>
</div>
</body>
</html>
