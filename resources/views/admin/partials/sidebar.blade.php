<aside class="sidebar p-3">
    <nav class="sidebar-nav">
        <div class="nav-item mt-3 fw-bold" style="font-size:15px;color:#0e7490;">
            عناصر الطب البشري
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2" style="color:#6366f1;"></i>
                لوحة البيانات
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.med_devices.index') }}"
               class="nav-link {{ request()->is('admin/devices*') ? 'active' : '' }}">
                <i class="bi bi-cpu" style="color:#0ea5e9;"></i>
                الأجهزة
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.med_subjects.index') }}"
               class="nav-link {{ request()->is('admin/subjects*') ? 'active' : '' }}">
                <i class="bi bi-journal-text" style="color:#22c55e;"></i>
                المواد
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.med_topics.index') }}"
               class="nav-link {{ request()->is('admin/topics*') ? 'active' : '' }}">
                <i class="bi bi-list-ul" style="color:#eab308;"></i>
                المواضيع
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.med_doctors.index') }}"
               class="nav-link {{ request()->is('admin/doctors*') ? 'active' : '' }}">
                <i class="bi bi-person-badge" style="color:#f97316;"></i>
                الدكاترة
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.med_videos.index') }}"
               class="nav-link {{ request()->is('admin/videos*') ? 'active' : '' }}">
                <i class="bi bi-youtube" style="color:#ef4444;"></i>
                الفيديوهات
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.med_resource-categories.index') }}"
               class="nav-link {{ request()->is('admin/resource-categories*') ? 'active' : '' }}">
                <i class="bi bi-tags" style="color:#14b8a6;"></i>
                تصنيفات الملفات
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.med_resources.index') }}"
               class="nav-link {{ request()->is('admin/resources*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-pdf" style="color:#a855f7;"></i>
                الملفات
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.users.index') }}"
                class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="bi bi-people" style="color:#22c55e;"></i> إدارة الطلاب
            </a>
        </div>

        <div class="nav-item mb-2">
            <a href="{{ route('admin.universities_management') }}"
                class="nav-link {{ request()->routeIs('admin.universities_management') ? 'active' : '' }}">
                <i class="bi bi-building-fill-check" style="color:#f97316;"></i>
                إدارة الجامعات والكليات
            </a>
        </div>

        {{-- العناصر المضافة --}}
        <div class="nav-item">
            <a href="{{ route('admin.medical_years.index') }}"
               class="nav-link {{ request()->is('admin/medical_years*') ? 'active' : '' }}">
                <i class="bi bi-calendar" style="color:#06b6d4;"></i>
                سنوات الطب (خاص)
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.medical_terms.index') }}"
               class="nav-link {{ request()->is('admin/medical_terms*') ? 'active' : '' }}">
                <i class="bi bi-journal" style="color:#0ea5e9;"></i>
                الفصول (خاص)
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.medical_subjects.index') }}"
               class="nav-link {{ request()->is('admin/medical_subjects*') ? 'active' : '' }}">
                <i class="bi bi-journal-text" style="color:#22c55e;"></i>
                مواد الطب (خاص)
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.medical_systems.index') }}"
               class="nav-link {{ request()->is('admin/medical_systems*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3" style="color:#f97316;"></i>
                الأنظمة (خاص)
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.medical_system_subjects.index') }}"
               class="nav-link {{ request()->is('admin/medical_system_subjects*') ? 'active' : '' }}">
                <i class="bi bi-link-45deg" style="color:#eab308;"></i>
                ربط الأنظمة بالمواد
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.medical_subject_contents.index') }}"
               class="nav-link {{ request()->is('admin/medical_subject_contents*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text" style="color:#ef4444;"></i>
                محتوى المواد (خاص)
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.medical_contents.index') }}"
   class="nav-link {{ request()->routeIs('admin.medical_contents.*') ? 'active' : '' }}">
    <i class="bi bi-file-medical" style="color:#ef4444;"></i>
    المحتوى الطبي (خاص)

            </a>
        </div>
    </nav>
</aside>