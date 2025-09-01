<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>دخول الأدمن</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container" style="max-width:460px; margin-top:80px;">
    <div class="card shadow-sm">
      <div class="card-body">
        <h5 class="mb-3 text-center">تسجيل دخول الأدمن</h5>

        <?php if($errors->any()): ?>
          <div class="alert alert-danger">
            <?php echo e($errors->first()); ?>

          </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('admin.login.post')); ?>">
          <?php echo csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control" required autofocus>
          </div>
          <div class="mb-3">
            <label class="form-label">كلمة المرور</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="remember" name="remember">
            <label class="form-check-label" for="remember">تذكرني</label>
          </div>
          <button class="btn btn-primary w-100">دخول</button>
        </form>

        <div class="text-center mt-3">
          <a href="<?php echo e(url('/')); ?>">العودة للموقع</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\campus\resources\views/admin/auth/login.blade.php ENDPATH**/ ?>