

<?php $__env->startSection('title','لوحة التحكم'); ?>

<?php $__env->startSection('content'); ?>
<?php
  // بيانات افتراضية في حال لم تُمرر من الكنترولر
  $kpis = $kpis ?? [
      ['title' => 'إجمالي الطلاب',      'value' => 12450, 'delta' => '+4.2%',  'icon' => 'users'],
      ['title' => 'المقررات النشطة',     'value' => 320,   'delta' => '+1.3%',  'icon' => 'book-open'],
      ['title' => 'هيئة التدريس',        'value' => 540,   'delta' => '-0.8%',  'icon' => 'briefcase'],
      ['title' => 'الإيرادات الشهرية',    'value' => 48720, 'delta' => '+12.6%', 'icon' => 'credit-card'],
  ];

  $months = ['ينا','فبر','مار','أبر','ماي','يون','يول','أغس','سبت','أكت','نوف','ديس'];

  $revenueSeries = $revenueSeries ?? [
      'labels' => $months,
      'data'   => [21000,22500,24800,26000,27800,30000,32200,34500,36800,41000,45500,48720],
  ];
  $usersSeries = $usersSeries ?? [
      'labels' => $months,
      'data'   => [820,860,910,950,990,1030,1080,1120,1180,1250,1310,1390],
  ];
  $deptDistribution = $deptDistribution ?? [
      'labels' => ['علوم الحاسب', 'هندسة', 'إدارة', 'آداب', 'علوم'],
      'data'   => [35,25,15,12,13],
  ];
  $recentEnrollments = $recentEnrollments ?? [
      ['student' => 'سارة أحمد', 'course' => 'CS101 - مقدمة برمجة',  'status' => 'enrolled',  'date' => '2025-08-29'],
      ['student' => 'محمد علي',  'course' => 'MG210 - مبادئ الإدارة','status' => 'completed', 'date' => '2025-08-28'],
      ['student' => 'أحمد سعيد', 'course' => 'EN150 - كتابة أكاديمية','status' => 'dropped',  'date' => '2025-08-27'],
      ['student' => 'ليان خالد', 'course' => 'EE201 - دوائر كهربائية','status' => 'enrolled',  'date' => '2025-08-26'],
      ['student' => 'رنا منصور', 'course' => 'CS240 - هياكل بيانات',  'status' => 'enrolled',  'date' => '2025-08-25'],
  ];
  $notifications = $notifications ?? [
      ['type' => 'warning', 'text' => 'نسبة الانسحاب زادت 3% هذا الأسبوع.'],
      ['type' => 'info',    'text' => 'تذكير: تسجيل الفصل القادم يبدأ 10 سبتمبر.'],
      ['type' => 'success', 'text' => 'اكتملت معالجة نتائج الفصل السابق.'],
  ];
  $tasks = $tasks ?? [
      ['title' => 'مراجعة مقررات قسم علوم الحاسب', 'done' => false],
      ['title' => 'اعتماد خطة التدريب لأعضاء هيئة التدريس', 'done' => true],
      ['title' => 'تحديث سياسة الحد الأدنى للساعات', 'done' => false],
  ];
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">نظرة عامة</h4>
  <div class="text-muted small">تاريخ اليوم: <?php echo e(now()->format('Y-m-d')); ?></div>
</div>


<div class="row g-3 mb-4">
  <?php $__currentLoopData = $kpis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kpi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php $isUp = str_starts_with($kpi['delta'], '+'); ?>
    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card card-kpi border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3">
            <div class="p-3 rounded-circle bg-light">
              <i data-feather="<?php echo e($kpi['icon']); ?>"></i>
            </div>
            <div class="flex-grow-1">
              <div class="text-muted small"><?php echo e($kpi['title']); ?></div>
              <div class="fs-4 fw-bold">
                <?php if($kpi['title'] === 'الإيرادات الشهرية'): ?>
                  $<?php echo e(number_format($kpi['value'])); ?>

                <?php else: ?>
                  <?php echo e(number_format($kpi['value'])); ?>

                <?php endif; ?>
              </div>
              <div class="delta <?php echo e($isUp ? 'up' : 'down'); ?> small"><?php echo e($kpi['delta']); ?></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="row g-3 mb-4">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0">الإيرادات الشهرية (آخر 12 شهر)</h6>
          <span class="text-muted small">USD</span>
        </div>
        <canvas id="revenueChart" height="110"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <h6 class="mb-3">توزيع الأقسام</h6>
        <canvas id="deptChart" height="180"></canvas>
      </div>
    </div>
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="mb-3">نمو المستخدمين</h6>
        <canvas id="usersChart" height="140"></canvas>
      </div>
    </div>
  </div>
</div>


<div class="row g-3">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h6 class="mb-0">أحدث عمليات التسجيل</h6>
          <a href="#" class="small">عرض الكل</a>
        </div>
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>الطالب</th>
                <th>المقرر</th>
                <th>الحالة</th>
                <th class="text-center">التاريخ</th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $recentEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                  <td><?php echo e($row['student']); ?></td>
                  <td><?php echo e($row['course']); ?></td>
                  <td>
                    <span class="badge status-badge <?php echo e($row['status']); ?>"><?php echo e($row['status']); ?></span>
                  </td>
                  <td class="text-center text-muted"><?php echo e($row['date']); ?></td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <h6 class="mb-3">التنبيهات</h6>
        <ul class="list-group list-group-flush">
          <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="list-group-item d-flex align-items-center gap-2">
              <?php if($n['type']==='warning'): ?>
                <span class="badge text-bg-warning">تنبيه</span>
              <?php elseif($n['type']==='success'): ?>
                <span class="badge text-bg-success">نجاح</span>
              <?php else: ?>
                <span class="badge text-bg-info">معلومة</span>
              <?php endif; ?>
              <span><?php echo e($n['text']); ?></span>
            </li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
    </div>

    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="mb-3">المهام</h6>
        <ul class="list-group list-group-flush">
          <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="list-group-item d-flex align-items-center justify-content-between">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" <?php echo e($t['done'] ? 'checked' : ''); ?> disabled>
                <label class="form-check-label"><?php echo e($t['title']); ?></label>
              </div>
              <span class="badge <?php echo e($t['done'] ? 'text-bg-success' : 'text-bg-secondary'); ?>">
                <?php echo e($t['done'] ? 'مكتمل' : 'قيد التنفيذ'); ?>

              </span>
            </li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
  const revenueLabels = <?php echo json_encode($revenueSeries['labels'], 15, 512) ?>;
  const revenueData   = <?php echo json_encode($revenueSeries['data'], 15, 512) ?>;

  const usersLabels = <?php echo json_encode($usersSeries['labels'], 15, 512) ?>;
  const usersData   = <?php echo json_encode($usersSeries['data'], 15, 512) ?>;

  const deptLabels = <?php echo json_encode($deptDistribution['labels'], 15, 512) ?>;
  const deptData   = <?php echo json_encode($deptDistribution['data'], 15, 512) ?>;

  // Revenue (Line)
  new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
      labels: revenueLabels,
      datasets: [{ label: 'الإيرادات', data: revenueData, tension: 0.35, fill: true }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: false } } }
  });

  // Departments (Doughnut)
  new Chart(document.getElementById('deptChart'), {
    type: 'doughnut',
    data: { labels: deptLabels, datasets: [{ data: deptData }] },
    options: { plugins: { legend: { position: 'bottom' } } }
  });

  // Users growth (Bar)
  new Chart(document.getElementById('usersChart'), {
    type: 'bar',
    data: { labels: usersLabels, datasets: [{ label: 'مستخدمون', data: usersData }] },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
  });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\campus\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>