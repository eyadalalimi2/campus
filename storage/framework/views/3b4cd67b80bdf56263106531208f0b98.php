
<?php $__env->startSection('title', 'الجامعات'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">الجامعات</h4>
        <a href="<?php echo e(route('admin.universities.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus"></i> جامعة جديدة
        </a>
    </div>

    
    <form class="row g-2 mb-3" method="GET" action="<?php echo e(route('admin.universities.index')); ?>">
        <div class="col-auto">
            <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control"
                placeholder="بحث بالاسم   ">
        </div>
        <div class="col-auto">
            <button class="btn btn-outline-secondary">بحث</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th style="width:72px">الشعار</th>
                    <th>الاسم</th>
                    <th>العنوان</th>
                    <th style="width:170px">رقم الهاتف</th>
                    <th>الحالة</th>
                    <th class="text-center" style="width:210px">إجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $universities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // تحديد رابط الشعار:
                        // 1) إن وُجد logo_url في السجل نستخدمه كما هو.
                        // 2) إن وُجد مسار ملف logo داخل التخزين العام نستخدم Storage::url().
                        // 3) وإلا نستخدم صورة افتراضية (ضعها عند public/images/logo.png أو بدّل المسار).
                        $logoSrc =
                            $u->logo_url ?:
                            ($u->logo ?? null
                                ? \Illuminate\Support\Facades\Storage::url($u->logo)
                                : asset('images/logo.png'));
                    ?>
                    <tr>
                        <td>
                            <img src="<?php echo e($logoSrc); ?>" alt="Logo" style="height:40px;width:auto;object-fit:contain">
                        </td>
                        <td class="fw-semibold"><?php echo e($u->name); ?></td>
                        <td class="text-muted"><?php echo e($u->address); ?></td>
                        <td>
                            <?php if(!empty($u->phone)): ?>
                                <a href="tel:<?php echo e(preg_replace('/\s+/', '', $u->phone)); ?>" class="text-decoration-none">
                                    <i class="bi bi-telephone"></i> <?php echo e($u->phone); ?>

                                </a>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($u->is_active): ?>
                                <span class="badge bg-success">مفعل</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">غير مفعل</span>
                            <?php endif; ?>
                        </td>

                        <td class="text-center">
                            <a href="<?php echo e(route('admin.universities.edit', $u)); ?>" class="btn btn-sm btn-outline-primary">
                                تعديل
                            </a>
                            <form action="<?php echo e(route('admin.universities.destroy', $u)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف الجامعة؟')">
                                    حذف
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">لا توجد بيانات.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php echo e($universities->withQueryString()->links()); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u127052915/domains/obdcodehub.com/campus/resources/views/admin/universities/index.blade.php ENDPATH**/ ?>