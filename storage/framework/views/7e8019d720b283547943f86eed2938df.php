
<?php $__env->startSection('title','تعديل دكتور'); ?>
<?php $__env->startSection('content'); ?>
<h4 class="mb-3">تعديل دكتور</h4>
<form action="<?php echo e(route('admin.doctors.update',$doctor)); ?>" method="POST" enctype="multipart/form-data" class="card p-3">
  <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
  <?php echo $__env->make('admin.doctors.form', ['doctor'=>$doctor,'selectedMajors'=>$selectedMajors], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <div class="mt-3">
    <button class="btn btn-primary">تحديث</button>
    <a href="<?php echo e(route('admin.doctors.index')); ?>" class="btn btn-link">رجوع</a>
  </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u127052915/domains/obdcodehub.com/campus/resources/views/admin/doctors/edit.blade.php ENDPATH**/ ?>