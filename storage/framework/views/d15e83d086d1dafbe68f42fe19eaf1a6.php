
<?php $__env->startSection('title','تعديل ثيم الجامعة'); ?>
<?php $__env->startSection('content'); ?>
<h4 class="mb-3">تعديل ثيم: <?php echo e($university->name); ?></h4>
<form action="<?php echo e(route('admin.themes.update',$university)); ?>" method="POST" enctype="multipart/form-data" class="card p-3">
  <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label">اسم الجامعة</label>
      <input type="text" name="name" class="form-control" value="<?php echo e(old('name',$university->name)); ?>" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">اللون الأساسي</label>
      <input type="color" name="primary_color" class="form-control form-control-color" value="<?php echo e(old('primary_color',$university->primary_color)); ?>" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">اللون الثانوي</label>
      <input type="color" name="secondary_color" class="form-control form-control-color" value="<?php echo e(old('secondary_color',$university->secondary_color)); ?>" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">الشعار</label>
      <input type="file" name="logo" class="form-control">
      <?php if($university->logo_url): ?>
        <img src="<?php echo e($university->logo_url); ?>" class="mt-2" style="height:48px">
      <?php endif; ?>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="<?php echo e(route('admin.themes.index')); ?>" class="btn btn-link">رجوع</a>
  </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u127052915/domains/obdcodehub.com/campus/resources/views/admin/themes/edit.blade.php ENDPATH**/ ?>