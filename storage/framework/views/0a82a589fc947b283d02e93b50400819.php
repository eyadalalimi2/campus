
<?php $__env->startSection('title','تعديل جامعة'); ?>
<?php $__env->startSection('content'); ?>
<h4 class="mb-3">تعديل جامعة</h4>
<form action="<?php echo e(route('admin.universities.update',$university)); ?>" method="POST" enctype="multipart/form-data">
  <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
  <?php echo $__env->make('admin.universities.form', ['university'=>$university], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <button class="btn btn-primary">تحديث</button>
  <a href="<?php echo e(route('admin.universities.index')); ?>" class="btn btn-link">رجوع</a>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u127052915/domains/obdcodehub.com/campus/resources/views/admin/universities/edit.blade.php ENDPATH**/ ?>