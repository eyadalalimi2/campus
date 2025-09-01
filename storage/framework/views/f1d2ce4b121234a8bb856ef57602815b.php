
<?php $__env->startSection('title','الدكاترة'); ?>
<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">الدكاترة</h4>
  <a href="<?php echo e(route('admin.doctors.create')); ?>" class="btn btn-primary"><i class="bi bi-plus"></i> إضافة دكتور</a>
</div>

<form class="row g-2 mb-3">
  <div class="col-md-2">
    <select name="type" class="form-select" onchange="this.form.submit()">
      <option value="">— الكل (النوع) —</option>
      <option value="university" <?php if(request('type')==='university'): echo 'selected'; endif; ?>>جامعي</option>
      <option value="independent" <?php if(request('type')==='independent'): echo 'selected'; endif; ?>>مستقل</option>
    </select>
  </div>
  <div class="col-md-3">
    <select name="university_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الجامعات —</option>
      <?php $__currentLoopData = $universities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($u->id); ?>" <?php if(request('university_id')==$u->id): echo 'selected'; endif; ?>><?php echo e($u->name); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>
  <div class="col-md-3">
    <select name="college_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل الكليات —</option>
      <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($c->id); ?>" <?php if(request('college_id')==$c->id): echo 'selected'; endif; ?>><?php echo e($c->name); ?> (<?php echo e($c->university->name); ?>)</option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>
  <div class="col-md-3">
    <select name="major_id" class="form-select" onchange="this.form.submit()">
      <option value="">— كل التخصصات —</option>
      <?php $__currentLoopData = $majors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($m->id); ?>" <?php if(request('major_id')==$m->id): echo 'selected'; endif; ?>><?php echo e($m->name); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
  </div>
  <div class="col-md-1">
    <input type="text" name="q" class="form-control" value="<?php echo e(request('q')); ?>" placeholder="بحث">
  </div>
</form>

<div class="table-responsive">
  <table class="table table-hover bg-white align-middle">
    <thead class="table-light">
      <tr>
        <th>الصورة</th><th>الاسم</th><th>النوع</th><th>الانتماء</th><th>التخصصات</th><th>الهاتف</th><th>الحالة</th><th class="text-center">إجراءات</th>
      </tr>
    </thead>
    <tbody>
      <?php $__empty_1 = true; $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <tr>
        <td><?php if($d->photo_url): ?><img src="<?php echo e($d->photo_url); ?>" style="height:40px;border-radius:8px"><?php endif; ?></td>
        <td><?php echo e($d->name); ?></td>
        <td><?php echo $d->type==='university' ? '<span class="badge bg-primary">جامعي</span>' : '<span class="badge bg-info text-dark">مستقل</span>'; ?></td>
        <td>
          <?php if($d->type==='university'): ?>
            <div class="small text-muted">
              <?php echo e(optional($d->university)->name); ?> / <?php echo e(optional($d->college)->name); ?> / <?php echo e(optional($d->major)->name); ?>

            </div>
          <?php else: ?>
            <span class="text-muted small">—</span>
          <?php endif; ?>
        </td>
        <td>
          <?php if($d->type==='university'): ?>
            <span class="badge bg-light text-dark"><?php echo e(optional($d->major)->name); ?></span>
          <?php else: ?>
            <?php $__currentLoopData = $d->majors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <span class="badge bg-light text-dark"><?php echo e($m->name); ?></span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <?php endif; ?>
        </td>
        <td><?php echo e($d->phone); ?></td>
        <td><?php echo $d->is_active ? '<span class="badge bg-success">مفعل</span>' : '<span class="badge bg-secondary">موقوف</span>'; ?></td>
        <td class="text-center">
          <a href="<?php echo e(route('admin.doctors.edit',$d)); ?>" class="btn btn-sm btn-outline-primary">تعديل</a>
          <form action="<?php echo e(route('admin.doctors.destroy',$d)); ?>" method="POST" class="d-inline">
            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف الدكتور؟')">حذف</button>
          </form>
        </td>
      </tr>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <tr><td colspan="8" class="text-center text-muted">لا توجد بيانات.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php echo e($doctors->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u127052915/domains/obdcodehub.com/campus/resources/views/admin/doctors/index.blade.php ENDPATH**/ ?>