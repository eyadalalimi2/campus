<aside class="sidebar p-3">
  <nav class="sidebar-nav">

    
    <div class="section-title">
      <i class="bi bi-list-task"></i> القائمة الرئيسية
    </div>
    <div class="nav-item">
      <a href="<?php echo e(route('admin.dashboard')); ?>"
         class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
        <i class="bi bi-speedometer2"></i>
        لوحة البيانات
      </a>
    </div>

    
    <div class="section-title mt-3">
      <i class="bi bi-building-fill-check"></i> إدارة الجامعات
    </div>
    <div class="nav-item">
      <a href="<?php echo e(route('admin.universities.index')); ?>"
         class="nav-link <?php echo e(request()->is('admin/universities*') ? 'active' : ''); ?>">
        <i class="bi bi-building"></i>
        الجامعات
      </a>
    </div>
    <div class="nav-item">
      <a href="<?php echo e(route('admin.colleges.index')); ?>"
         class="nav-link <?php echo e(request()->is('admin/colleges*') ? 'active' : ''); ?>">
        <i class="bi bi-bank"></i>
        الكليات
      </a>
    </div>
    <div class="nav-item">
      <a href="<?php echo e(route('admin.majors.index')); ?>"
         class="nav-link <?php echo e(request()->is('admin/majors*') ? 'active' : ''); ?>">
        <i class="bi bi-diagram-3"></i>
        التخصصات
      </a>
    </div>

    
    <div class="section-title mt-3">
      <i class="bi bi-gear-fill"></i> الإعدادات
    </div>
    <div class="nav-item">
      <a href="<?php echo e(route('admin.import.index')); ?>"
         class="nav-link <?php echo e(request()->is('admin/import*') ? 'active' : ''); ?>">
        <i class="bi bi-upload"></i>
        الاستيراد (Excel)
      </a>
    </div>
    <div class="nav-item">
      <a href="<?php echo e(route('admin.themes.index')); ?>"
         class="nav-link <?php echo e(request()->is('admin/themes*') ? 'active' : ''); ?>">
        <i class="bi bi-palette"></i>
        إدارة الثيمات
      </a>
    </div>
    <div class="nav-item">
      <a href="<?php echo e(route('admin.doctors.index')); ?>"
         class="nav-link <?php echo e(request()->is('admin/doctors*') ? 'active' : ''); ?>">
        <i class="bi bi-person-badge"></i>
        الدكاترة
      </a>
    </div>
    <div class="nav-item">
      <a href="<?php echo e(route('admin.contents.index')); ?>"
         class="nav-link <?php echo e(request()->is('admin/contents*') ? 'active' : ''); ?>">
        <i class="bi bi-folder"></i>
        المحتوى
      </a>
    </div>
    <div class="nav-item">
      <a href="<?php echo e(route('admin.materials.index')); ?>"
         class="nav-link <?php echo e(request()->is('admin/materials*') ? 'active' : ''); ?>">
        <i class="bi bi-journal-text"></i>
        المواد
      </a>
    </div>
    <div class="nav-item">
      <a href="<?php echo e(route('admin.devices.index')); ?>"
         class="nav-link <?php echo e(request()->is('admin/devices*') ? 'active' : ''); ?>">
        <i class="bi bi-cpu"></i>
        الأجهزة / المهام
      </a>
    </div>
    <div class="nav-item">
      <a href="<?php echo e(route('admin.assets.index')); ?>"
         class="nav-link <?php echo e(request()->is('admin/assets*') ? 'active' : ''); ?>">
        <i class="bi bi-collection"></i>
        العناصر التعليمية
      </a>
    </div>

  </nav>
</aside>
<?php /**PATH /home/u127052915/domains/obdcodehub.com/campus/resources/views/admin/partials/sidebar.blade.php ENDPATH**/ ?>