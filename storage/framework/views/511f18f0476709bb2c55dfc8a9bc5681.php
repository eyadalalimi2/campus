<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'لوحة التحكم'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: #f6f7fb;
        }

        :root {
            --sidebar-bg: #ffffff;
            --sidebar-border: #eef0f4;
            --sidebar-text: #1f2837;
            --sidebar-muted: #6b7280;
            --sidebar-hover: #f3f6fb;
            --sidebar-active: #0d6efd;
            /* لون التفعيل */
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --sidebar-bg: #0f1520;
                --sidebar-border: #1b2432;
                --sidebar-text: #e5e7eb;
                --sidebar-muted: #9aa4b2;
                --sidebar-hover: #161e2b;
                --sidebar-active: #3b82f6;
            }
        }

        .sidebar {
            background: var(--sidebar-bg);
            border: 1px solid var(--sidebar-border);
            border-radius: .85rem;
            box-shadow: 0 10px 24px rgba(0, 0, 0, .06);
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: .25rem;
        }

        .sidebar .section-title {
            margin: .75rem .75rem .25rem;
            color: var(--sidebar-muted);
            font-weight: 700;
            font-size: .78rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            letter-spacing: .02em;
        }

        .sidebar .section-title i {
            font-size: 1rem;
            opacity: .9;
        }

        .sidebar .nav-item {
            position: relative;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .60rem .9rem;
            border-radius: .65rem;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: background .18s ease, color .18s ease, transform .06s ease;
            margin: 0 .4rem;
        }

        .sidebar .nav-link i {
            font-size: 1rem;
            width: 1.25rem;
            text-align: center;
            opacity: .95;
        }

        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
        }

        /* مؤشر التفعيل (شريط جانبي أيمن لأن الاتجاه RTL) */
        [dir="rtl"] .sidebar .nav-link.active::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 3px;
            border-radius: 3px 0 0 3px;
            background: var(--sidebar-active);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--sidebar-active) 18%, transparent);
        }

        /* في LTR (لو بدّلت اللغة مستقبلاً) */
        [dir="ltr"] .sidebar .nav-link.active::before {
            inset: 0 0 0 auto;
            border-radius: 0 3px 3px 0;
        }

        .sidebar .nav-link.active {
            background: color-mix(in srgb, var(--sidebar-active) 8%, transparent);
            color: var(--sidebar-active);
            font-weight: 700;
        }

        /* شارة مساعدة (اختياري) */
        .sidebar .badge-soft {
            margin-inline-start: auto;
            background: color-mix(in srgb, var(--sidebar-active) 10%, transparent);
            color: var(--sidebar-active);
            border: 1px solid color-mix(in srgb, var(--sidebar-active) 25%, transparent);
            font-weight: 600;
            font-size: .72rem;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <?php echo $__env->make('admin.partials.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="container-fluid my-4">
        <div class="row g-4">
            <aside class="col-lg-2 d-none d-lg-block">
                <?php echo $__env->make('admin.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </aside>
            <main class="col-lg-10">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <?php echo $__env->make('admin.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        feather.replace();
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH /home/u127052915/domains/obdcodehub.com/campus/resources/views/admin/layouts/app.blade.php ENDPATH**/ ?>