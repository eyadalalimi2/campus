
<?php $__env->startSection('title','التخصصات'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">التخصصات</h4>
  <a href="<?php echo e(route('admin.majors.create')); ?>" class="btn btn-primary"><i class="bi bi-plus"></i> تخصص جديد</a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-4">
    <select name="university_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الجامعات —</option>
      <?php $__currentLoopData = $universities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($u->id); ?>" <?php if(request('university_id')==$u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>
  <div class="col-md-4">
    <select name="college_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الكليات —</option>
      <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($c->id); ?>" <?php if(request('college_id')==$c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?> (<?php echo e($c->university->name); ?>)</option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>
  <div class="col-md-3">
    <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control" placeholder="بحث بالاسم">
  </div>
  <div class="col-md-1">
    <button class="btn btn-outline-secondary w-100">بحث</button>
  </div>
</form>

<div class="table-responsive">
  <table class="table table-hover bg-white">
    <thead class="table-light">
      <tr><th>التخصص</th><th>الكلية</th><th>الجامعة</th><th>الرمز</th><th>الحالة</th><th class="text-center">إجراءات</th></tr>
    </thead>
    <tbody>
      <?php $__empty_1 = true; $__currentLoopData = $majors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <tr>
        <td><?php echo e($m->name); ?></td>
        <td><?php echo e($m->college->name); ?></td>
        <td><?php echo e($m->college->university->name); ?></td>
        <td><?php echo e($m->code); ?></td>
        <td><?php echo $m->is_active ? '<span class="badge bg-success">مفعل</span>' : '<span class="badge bg-secondary">موقوف</span>'; ?></td>
        <td class="text-center">
          <a class="btn btn-sm btn-outline-primary" href="<?php echo e(route('admin.majors.edit',$m)); ?>">تعديل</a>
          <form action="<?php echo e(route('admin.majors.destroy',$m)); ?>" method="POST" class="d-inline"><?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف التخصص؟')">حذف</button>
          </form>
        </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <tr><td colspan="6" class="text-center text-muted">لا توجد بيانات.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php echo e($majors->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u127052915/domains/obdcodehub.com/campus/resources/views/admin/majors/index.blade.php ENDPATH**/ ?>