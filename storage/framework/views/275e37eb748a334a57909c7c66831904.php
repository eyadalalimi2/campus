

<?php $__env->startSection('title','الصفحة الرئيسية'); ?>

<?php $__env->startSection('content'); ?>
<?php
  // بيانات افتراضية في حال لم تُمرر من الكنترولر
  $headlineStats = $headlineStats ?? [
      ['label' => 'الطلاب', 'value' => 12450],
      ['label' => 'المقررات', 'value' => 320],
      ['label' => 'هيئة التدريس', 'value' => 540],
      ['label' => 'أقسام', 'value' => 18],
  ];

  $usps = $usps ?? [
      ['title' => 'إدارة أكاديمية شاملة', 'desc' => 'أقسام، مقررات، طلاب، وأعضاء هيئة تدريس في نظام موحّد.'],
      ['title' => 'تقارير فورية', 'desc' => 'لوحات متابعة لحظية مع رسوم بيانية لاتخاذ قرار سريع.'],
      ['title' => 'قابلية التوسع', 'desc' => 'بنية مرنة تدعم التكامل مع الأنظمة المؤسسية.'],
  ];
?>

<div class="hero py-5" style="background: linear-gradient(135deg,#f1f5ff,#fff);">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-6">
        <h1 class="display-5 fw-bold mb-3">منصّة جامعية لإدارة الأكاديمية بكفاءة</h1>
        <p class="text-muted mb-4">
          إدارة الأقسام، المقررات، الطلاب، وأعضاء هيئة التدريس — في نظام موحّد سريع ومرن، مع تقارير لحظية.
        </p>
        <div class="d-flex gap-2">
          <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-primary btn-lg">استعراض لوحة التحكم</a>
          <a href="#" class="btn btn-outline-secondary btn-lg">تعرّف أكثر</a>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="row g-3">
          <?php $__currentLoopData = $headlineStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-6">
              <div class="p-4 text-center" style="border-radius:.75rem; background:#fff; box-shadow:0 6px 16px rgba(0,0,0,.06);">
                <div class="display-6 fw-bold"><?php echo e(number_format($s['value'])); ?></div>
                <div class="text-muted"><?php echo e($s['label']); ?></div>
              </div>
            </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
    </div>
  </div>
</div>

<section class="py-5">
  <div class="container">
    <div class="text-center mb-4">
      <h2 class="h3">لماذا Campus؟</h2>
      <p class="text-muted">قيم تشغيل عالية، قرارات أسرع، ورؤية موحّدة.</p>
    </div>
    <div class="row g-3">
      <?php $__currentLoopData = $usps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4">
          <div class="card h-100" style="border:0; box-shadow:0 6px 16px rgba(0,0,0,.06); border-radius:.75rem;">
            <div class="card-body">
              <h5 class="card-title"><?php echo e($f['title']); ?></h5>
              <p class="card-text text-muted"><?php echo e($f['desc']); ?></p>
              <a class="stretched-link" href="<?php echo e(route('admin.dashboard')); ?>"></a>
            </div>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
  </div>
</section>

<section class="py-5 bg-light">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-6">
        <h3 class="fw-bold mb-3">تكامل سريع مع أنظمتك الحالية</h3>
        <p class="text-muted mb-4">واجهات برمجية وعمليات استيراد/تصدير بيانات تسهّل الهجرة من الأنظمة القديمة. قابلية لإدارة الأذونات والأدوار.</p>
        <div class="d-flex gap-2">
          <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-primary">ابدأ الآن</a>
          <a href="#" class="btn btn-outline-secondary">تواصل معنا</a>
        </div>
      </div>
      <div class="col-lg-6">
        <img class="img-fluid rounded shadow-sm" src="https://picsum.photos/960/540" alt="Campus Preview">
      </div>
    </div>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('site.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\campus\resources\views/site/home.blade.php ENDPATH**/ ?>