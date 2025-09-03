<aside class="sidebar p-3">
    <nav class="sidebar-nav">

        {{-- القائمة الرئيسية --}}
        <div class="section-title">
            <i class="bi bi-list-task"></i> القائمة الرئيسية
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                لوحة البيانات
            </a>
        </div>

        {{-- إدارة الجامعات والكليات --}}
        <div class="section-title mt-3">
            <i class="bi bi-building-fill-check"></i> إدارة الجامعات والكليات
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.universities.index') }}"
                class="nav-link {{ request()->is('admin/universities*') ? 'active' : '' }}">
                <i class="bi bi-building"></i>
                إدارة الجامعات
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.colleges.index') }}"
                class="nav-link {{ request()->is('admin/colleges*') ? 'active' : '' }}">
                <i class="bi bi-bank"></i>
                إدارة الكليات
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.majors.index') }}"
                class="nav-link {{ request()->is('admin/majors*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3"></i>
                إدارة التخصصات والأقسام
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.doctors.index') }}"
                class="nav-link {{ request()->is('admin/doctors*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>
                إدارة الدكاترة
            </a>
        </div>

        {{-- إدارة العناصر التعليمية --}}
        <div class="section-title mt-3">
            <i class="bi bi-journal-check"></i> إدارة العناصر التعليمية
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.materials.index') }}"
                class="nav-link {{ request()->is('admin/materials*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i>
                إدارة المواد
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.devices.index') }}"
                class="nav-link {{ request()->is('admin/devices*') ? 'active' : '' }}">
                <i class="bi bi-cpu"></i>
                إدارة الأجهزة
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.assets.index') }}"
                class="nav-link {{ request()->is('admin/assets*') ? 'active' : '' }}">
                <i class="bi bi-collection"></i>
                إدارة العناصر التعليمية
            </a>
        </div>

        {{-- إدارة الطلاب --}}
        <div class="nav-item">
            <a href="{{ route('admin.users.index') }}"
                class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> إدارة الطلاب
            </a>
        </div>
        {{-- إدارة المحتوى --}}
        <div class="section-title mt-3">
            <i class="bi bi-folder2-open"></i> إدارة المحتوى
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.contents.index') }}"
                class="nav-link {{ request()->is('admin/contents*') ? 'active' : '' }}">
                <i class="bi bi-folder"></i>
                إدارة المحتوى
            </a>
        </div>

        {{-- إدارة الاستيراد --}}
        <div class="section-title mt-3">
            <i class="bi bi-cloud-arrow-up-fill"></i> إدارة الاستيراد
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.import.index') }}"
                class="nav-link {{ request()->is('admin/import*') ? 'active' : '' }}">
                <i class="bi bi-upload"></i>
                الاستيراد (Excel)
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.themes.index') }}"
                class="nav-link {{ request()->is('admin/themes*') ? 'active' : '' }}">
                <i class="bi bi-palette"></i>
                إدارة الثيمات
            </a>
        </div>
        <div class="section-title mt-3">
            <i class="bi bi-journal-richtext"></i> المدونة والاشتراكات
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.blogs.index') }}"
                class="nav-link {{ request()->is('admin/blogs*') ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i> المدونات
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.subscriptions.index') }}"
                class="nav-link {{ request()->is('admin/subscriptions*') ? 'active' : '' }}">
                <i class="bi bi-credit-card-2-front"></i> الاشتراكات
            </a>
        </div>


        {{-- روابط للعرض فقط (غير مرتبطة بصفحات فعلية) --}}
        <div class="section-title mt-3">
            <i class="bi bi-eye"></i> ميزات مستقبلية
        </div>
        
        <div class="nav-item">
            <a href="javascript:void(0)" class="nav-link text-muted" title="عرض فقط" onclick="return false;">
                <i class="bi bi-sliders"></i>
                إعدادات النظام
            </a>
        </div>
        <div class="nav-item">
            <a href="javascript:void(0)" class="nav-link text-muted" title="عرض فقط" onclick="return false;">
                <i class="bi bi-person-gear"></i>
                إدارة المستخدمين
            </a>
        </div>
        <div class="nav-item">
            <a href="javascript:void(0)" class="nav-link text-muted" title="عرض فقط" onclick="return false;">
                <i class="bi bi-person-gear"></i>
                مناهج الثانوية
            </a>
        </div>
        <div class="nav-item">
            <a href="javascript:void(0)" class="nav-link text-muted" title="عرض فقط" onclick="return false;">
                <i class="bi bi-person-gear"></i>
                إدارة التقارير
            </a>
        </div>


    </nav>
</aside>
