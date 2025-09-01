<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top" style="min-height:70px;">
    <div class="container-fluid">

        
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?php echo e(route('admin.dashboard')); ?>">
            <img src="<?php echo e(Storage::url('images/logo.png')); ?>" style="height:36px;width:auto;">

            <span>لوحة التحكم</span>
            <?php if(isset($currentUniversity)): ?>
                <?php if(!empty($currentUniversity)): ?>
                    <span class="badge bg-primary"><?php echo e($currentUniversity->name); ?></span>
                <?php endif; ?>
            <?php endif; ?>
        </a>

        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminTopbar"
            aria-controls="adminTopbar" aria-expanded="false" aria-label="تبديل القائمة">
            <span class="navbar-toggler-icon"></span>
        </button>

        
        <div class="collapse navbar-collapse" id="adminTopbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('site.home')); ?>">
                        <i class="bi bi-globe2"></i> الموقع
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)">
                        <i class="bi bi-gear"></i> الإعدادات
                    </a>
                </li>


                <?php if(auth()->guard('admin')->check()): ?>
                    
                    <li class="nav-item d-none d-lg-block">
                        <span class="vr mx-2" style="opacity:.2;"></span>
                    </li>

                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span class="small">
                                <?php echo e(auth('admin')->user()->name ?? 'مدير النظام'); ?>

                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li class="dropdown-header small text-muted">
                                حساب المشرف
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">
                                    <i class="bi bi-person-gear me-2"></i> الملف الشخصي
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)">
                                    <i class="bi bi-clipboard-check me-2"></i> سجلات التدقيق
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="<?php echo e(route('admin.logout')); ?>" method="POST" class="m-0 p-0">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> خروج
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


<style>
    @media (prefers-color-scheme: dark) {
        nav.navbar.bg-white {
            background: #0f1520 !important;
        }

        nav.navbar .navbar-brand,
        nav.navbar .nav-link {
            color: #e5e7eb !important;
        }

        nav.navbar .border-bottom {
            border-color: #1b2432 !important;
        }

        .dropdown-menu {
            background: #0f1520;
            border-color: #1b2432;
        }

        .dropdown-item {
            color: #e5e7eb;
        }

        .dropdown-item:hover {
            background: #161e2b;
        }

        .vr {
            border-color: #1b2432 !important;
        }
    }
</style>
<?php /**PATH C:\xampp\htdocs\campus\resources\views/admin/partials/navbar.blade.php ENDPATH**/ ?>