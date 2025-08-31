<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="direction:ltr;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('admin.dashboard') }}" class="nav-link arabic">لوحة التحكم</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @php
            $u = auth()->user();
            $profileImage = $u && $u->profile_image
                ? (preg_match('#^https?://#', $u->profile_image)
                    ? $u->profile_image
                    : (Str::startsWith($u->profile_image, 'storage/')
                        ? asset($u->profile_image)
                        : asset('storage/'.$u->profile_image)))
                : asset('images/user-placeholder.png');
        @endphp

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" role="button" title="البروفايل">
                <img src="{{ $profileImage }}" class="rounded-circle" style="width:32px;height:32px;object-fit:cover;vertical-align:middle;">
                <span class="ml-2 arabic">{{ $u->name ?? 'المشرف' }}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('admin.profile') }}" class="dropdown-item arabic">
                    <i class="fas fa-user"></i> الملف الشخصي
                </a>
                <div class="dropdown-divider"></div>
                <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item arabic">
                        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                    </button>
                </form>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="شاشة كاملة">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.settings.index') }}" class="nav-link arabic"><i class="fas fa-cogs"></i> الإعدادات</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.navigation') }}" class="nav-link arabic"><i class="fas fa-th-large"></i> التنقل</a>
        </li>

        <li class="nav-item">
            <a href="#" class="nav-link arabic"><i class="fas fa-language"></i> اللغة</a>
        </li>
    </ul>
</nav>
