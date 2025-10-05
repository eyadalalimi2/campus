<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top" style="min-height:70px;">
    <div class="container-fluid">

        <!-- زر القائمة الجانبية للشاشات الصغيرة قبل اللوجو والاسم -->
        <button class="btn btn-outline-primary d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar" aria-controls="adminSidebar">
            <i class="bi bi-list"></i>
        </button>
        {{-- Brand + Logo + Current University Badge --}}
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
            <img src="{{ Storage::url('images/logo.png') }}" style="height:36px;width:auto;">

            <span>لوحة التحكم</span>
            @isset($currentUniversity)
                @if (!empty($currentUniversity))
                    <span class="badge bg-primary">{{ $currentUniversity->name }}</span>
                @endif
            @endisset
            <!-- أيقونة ملء الشاشة -->
            <button id="fullscreen-toggle" type="button" class="btn btn-link p-0 ms-2" title="وضع ملء الشاشة" style="font-size:1.5rem;">
                <i class="bi bi-arrows-fullscreen"></i>
            </button>
        </a>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('fullscreen-toggle');
        if(btn) {
            btn.addEventListener('click', function() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                }
                // للخروج من ملء الشاشة يجب الضغط مرة أخرى أو استخدام زر الخروج من المتصفح
            });
        }
    });
</script>
@endpush

        {{-- Toggler (Mobile) --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminTopbar"
            aria-controls="adminTopbar" aria-expanded="false" aria-label="تبديل القائمة">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Collapsible Area --}}
        <div class="collapse navbar-collapse" id="adminTopbar">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                    {{-- إدارة البنرات والشكاوى والإشعارات والطلبات --}}
                    <li class="nav-item">
                    {{-- تم نقل روابط البنرات والشكاوى والإشعارات والطلبات إلى القائمة السفلية --}}

                {{-- روابط عامة --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('site.home') }}">
                        <i class="bi bi-globe2"></i> الموقع
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0)">
                        <i class="bi bi-gear"></i> الإعدادات
                    </a>
                </li>


                @auth('admin')
                    {{-- فاصل بسيط على الشاشات الكبيرة --}}
                    <li class="nav-item d-none d-lg-block">
                        <span class="vr mx-2" style="opacity:.2;"></span>
                    </li>

                    {{-- User Dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5"></i>
                            <span class="small">
                                {{ auth('admin')->user()->name ?? 'مدير النظام' }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li class="dropdown-header small text-muted">
                                حساب المشرف
                            </li>
                            <li>
                                <a class="dropdown-item" href="javascript:void(0)">
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
                                <form action="{{ route('admin.logout') }}" method="POST" class="m-0 p-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> خروج
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- تحسينات نمطية خفيفة (اختياري) --}}
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
