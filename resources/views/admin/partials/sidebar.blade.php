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

        {{-- الإدارة الأكاديمية --}}
        <div class="section-title mt-3">
            <i class="bi bi-mortarboard"></i> الإدارة الأكاديمية
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.countries.index') }}"
               class="nav-link {{ request()->is('admin/countries*') ? 'active' : '' }}">
                <i class="bi bi-globe2"></i> إدارة الدول
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.disciplines.index') }}"
               class="nav-link {{ request()->is('admin/disciplines*') ? 'active' : '' }}">
                <i class="bi bi-bookmark"></i> المجالات
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.programs.index') }}"
               class="nav-link {{ request()->is('admin/programs*') ? 'active' : '' }}">
                <i class="bi bi-collection"></i> البرامج
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.academic-calendars.index') }}"
               class="nav-link {{ request()->is('admin/academic-calendars*') ? 'active' : '' }}">
                <i class="bi bi-calendar4-week"></i> التقويم الأكاديمي
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.academic-terms.index') }}"
               class="nav-link {{ request()->is('admin/academic-terms*') ? 'active' : '' }}">
                <i class="bi bi-calendar"></i> الفصول الأكاديمية
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.major_program.index') }}"
               class="nav-link {{ request()->is('admin/major-programs*') ? 'active' : '' }}">
                <i class="bi bi-diagram-2"></i>
                ربط التخصص ↔ البرنامج
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

        {{-- الخطط والاشتراكات --}}
        <div class="section-title mt-3">
            <i class="bi bi-bag-check"></i> الخطط والاشتراكات
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.plans.index') }}"
               class="nav-link {{ request()->is('admin/plans*') ? 'active' : '' }}">
                <i class="bi bi-card-checklist"></i> الخطط
            </a>
        </div>
        
        <div class="nav-item">
            <a href="{{ route('admin.subscriptions.index') }}"
               class="nav-link {{ request()->is('admin/subscriptions*') ? 'active' : '' }}">
                <i class="bi bi-credit-card-2-front"></i> الاشتراكات
            </a>
        </div>

        {{-- أكواد التفعيل --}}
        <div class="section-title mt-3">
            <i class="bi bi-key"></i> أكواد التفعيل
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.activation_code_batches.index') }}"
               class="nav-link {{ request()->is('admin/activation-code-batches*') ? 'active' : '' }}">
                <i class="bi bi-boxes"></i> دفعات الأكواد
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.activation_codes.index') }}"
               class="nav-link {{ request()->is('admin/activation-codes*') ? 'active' : '' }}">
                <i class="bi bi-qr-code"></i> الأكواد الفردية
            </a>
        </div>

        {{-- إدارة الاستيراد والثيمات --}}
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

        {{-- المدونة --}}
        <div class="section-title mt-3">
            <i class="bi bi-journal-richtext"></i> المدونة
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.blogs.index') }}"
               class="nav-link {{ request()->is('admin/blogs*') ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i> المدونات
            </a>
        </div>

        {{-- ميزات مستقبلية (روابط غير مفعلة حالياً) --}}
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
                <i class="bi bi-journal-medical"></i>
                مناهج الثانوية
            </a>
        </div>
        <div class="nav-item">
            <a href="javascript:void(0)" class="nav-link text-muted" title="عرض فقط" onclick="return false;">
                <i class="bi bi-graph-up"></i>
                إدارة التقارير
            </a>
        </div>

    </nav>
</aside>
