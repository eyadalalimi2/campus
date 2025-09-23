<aside class="sidebar p-3">

    <nav class="sidebar-nav">
        <div class="nav-item mt-3" style="font-weight:bold;font-size:15px;color:#0e7490;">عناصر الطب البشري</div>
        <div class="nav-item">
            <a href="{{ route('medical.systems.index') }}"
                class="nav-link {{ request()->is('admin/medical/systems*') ? 'active' : '' }}">
                <i class="bi bi-cpu"></i> الأجهزة
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('medical.subjects.index') }}"
                class="nav-link {{ request()->is('admin/medical/subjects*') ? 'active' : '' }}">
                <i class="bi bi-journal-bookmark"></i> المواد
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('medical.doctors.index') }}"
                class="nav-link {{ request()->is('admin/medical/doctors*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> الدكاترة
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('medical.resources.index') }}"
                class="nav-link {{ request()->is('admin/medical/resources*') ? 'active' : '' }}">
                <i class="bi bi-collection-play"></i> الموارد
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('medical.universities.index') }}"
                class="nav-link {{ request()->is('admin/medical/universities*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> الجامعات
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('medical.doctor-subjects.index') }}"
                class="nav-link {{ request()->is('admin/medical/doctor-subjects*') ? 'active' : '' }}">
                <i class="bi bi-link-45deg"></i> ربط دكتور↔مادة
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('medical.doctor-subject-systems.index') }}"
                class="nav-link {{ request()->is('admin/medical/doctor-subject-systems*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3"></i> ربط دكتور↔مادة↔جهاز
            </a>
        </div>
        
        <div class="nav-item">
            <a href="{{ route('medical.system-subjects.index') }}"
                class="nav-link {{ request()->is('admin/medical/system-subjects*') ? 'active' : '' }}">
                <i class="bi bi-link"></i> ربط جهاز↔مادة
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                لوحة البيانات
            </a>
        </div>
        <div class="nav-item mb-2">
            <a href="{{ route('admin.universities_management') }}"
                class="nav-link {{ request()->routeIs('admin.universities_management') ? 'active' : '' }}">
                <i class="bi bi-building-fill-check"></i>
                إدارة الجامعات والكليات
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.public-colleges.index') }}"
                class="nav-link {{ request()->is('admin/public-colleges*') ? 'active' : '' }}">
                <i class="bi bi-bank2"></i>
                الكليات العامة
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.public-majors.index') }}"
                class="nav-link {{ request()->is('admin/public-majors*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3-fill"></i>
                التخصصات العامة
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.users.index') }}"
                class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> إدارة الطلاب
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.doctors.index') }}"
                class="nav-link {{ request()->is('admin/doctors*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>
                إدارة الدكاترة
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.assets.index') }}"
                class="nav-link {{ request()->is('admin/assets*') ? 'active' : '' }}">
                <i class="bi bi-collection"></i>
                إدارة المحتوى العام
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.contents.index') }}"
                class="nav-link {{ request()->is('admin/contents*') ? 'active' : '' }}">
                <i class="bi bi-folder"></i>
                إدارة المحتوى الخاص
            </a>
        </div>
    </nav>
</aside>
