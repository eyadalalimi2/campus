<aside class="sidebar p-3">
    <nav class="sidebar-nav">
        <div class="nav-item mt-3 fw-bold" style="font-size:15px;color:#0e7490;">
            عناصر الطب البشري
        </div>
        <!-- رابط نصائح طبية (شاشة مستقلة) -->
        <div class="nav-item">
            <a href="{{ route('admin.medical_tips.index') }}" class="nav-link {{ request()->is('admin/medical_tips*') ? 'active' : '' }}">
                <i class="bi bi-heart-pulse" style="color:#ef4444;"></i>
                نصائح طبية
            </a>
        </div>

        <!-- رابط الدورات والأنشطة (أوّل عنصر في القائمة) -->
        <div class="nav-item">
            <a href="{{ route('admin.activity_buttons.index') }}" class="nav-link {{ request()->is('admin/activity_buttons*') ? 'active' : '' }}">
                <i class="bi bi-easel2" style="color:#0ea5e9;"></i>
                الدورات والأنشطة
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.practice_pdfs.index') }}"
                class="nav-link {{ request()->routeIs('admin.practice_pdfs.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text" style="color:#7c3aed;"></i>
                ملفات اختبار مزاولة المهنة
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.research_pdfs.index') }}"
                class="nav-link {{ request()->routeIs('admin.research_pdfs.*') ? 'active' : '' }}">
                <i class="bi bi-journal" style="color:#0ea5e9;"></i>
                الأبحاث ورسائل الماجستير
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.imports.index') }}"
                class="nav-link {{ request()->is('admin/imports*') ? 'active' : '' }}">
                <i class="bi bi-upload" style="color:#0ea5e9;"></i> استيراد بيانات
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.content_imports.index') }}"
                class="nav-link {{ request()->is('admin/content-imports*') ? 'active' : '' }}">
                <i class="bi bi-cloud-upload" style="color:#ef4444;"></i>
                استيراد المحتوى
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
                class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2" style="color:#6366f1;"></i>
                لوحة البيانات
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
            <a href="{{ route('admin.med_devices.index') }}"
                class="nav-link {{ request()->is('admin/devices*') ? 'active' : '' }}">
                <i class="bi bi-cpu" style="color:#0ea5e9;"></i>
                الأجهزة
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

        {{-- تم نقل تصنيفات الملفات إلى القائمة السفلية --}}

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

        {{-- تم نقل إدارة الجامعات والكليات إلى القائمة السفلية --}}

        {{-- تم نقل اكواد التفعيل إلى القائمة السفلية --}}

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
            <a href="{{ route('admin.medical_contents.index') }}"
                class="nav-link {{ request()->routeIs('admin.medical_contents.*') ? 'active' : '' }}">
                <i class="bi bi-file-medical" style="color:#ef4444;"></i>
                المحتوى الطبي (خاص)

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
            <a href="{{ route('admin.clinical_subjects.index') }}"
                class="nav-link {{ request()->routeIs('admin.clinical_subjects.*') ? 'active' : '' }}">
                <i class="bi bi-hospital" style="color:#0ea5e9;"></i>
                المواد السريرية
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.clinical_subject_pdfs.index') }}"
                class="nav-link {{ request()->routeIs('admin.clinical_subject_pdfs.*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-pdf" style="color:#a855f7;"></i>
                ملفات PDF السريرية
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('admin.study_guides.index') }}"
                class="nav-link {{ request()->routeIs('admin.study_guides.*') ? 'active' : '' }}">
                <i class="bi bi-mortarboard" style="color:#06b6d4;"></i>
                أدلة المذاكرة
            </a>
        </div>
        <hr>
        <div class="mt-3">
            <div class="nav-item">
                <a href="{{ route('admin.activation_codes.index') }}" class="nav-link">
                    <i class="bi bi-key" style="color:#6366f1;"></i> اكواد التفعيل
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.med_resource-categories.index') }}" class="nav-link">
                    <i class="bi bi-tags" style="color:#14b8a6;"></i> تصنيفات الملفات
                </a>
            </div>
            {{-- <div class="nav-item">
                        <a href="{{ route('admin.universities_management') }}" class="nav-link">
                            <i class="bi bi-building-fill-check" style="color:#f97316;"></i> إدارة الجامعات والكليات
                        </a>
                    </div> --}}
            <div class="nav-item">
                <a href="{{ route('admin.banners.index') }}" class="nav-link">
                    <i class="bi bi-image" style="color:#f59e42;"></i> البنرات
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.notifications.index') }}" class="nav-link">
                    <i class="bi bi-bell" style="color:#facc15;"></i> الإشعارات
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                    <i class="bi bi-star-half" style="color:#f59e0b;"></i> التقييمات
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.complaints.index') }}" class="nav-link">
                    <i class="bi bi-exclamation-diamond" style="color:#e11d48;"></i> الشكاوى
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.requests.index') }}" class="nav-link">
                    <i class="bi bi-envelope-paper" style="color:#6366f1;"></i> الطلبات
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.themes.index') }}" class="nav-link">
                    <i class="bi bi-palette" style="color:#10b981;"></i> إدارة الثيمات
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.courses.index') }}" class="nav-link">
                    <i class="bi bi-journal-bookmark" style="color:#0e7490;"></i> الكورسات
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.content_assistants.index') }}" class="nav-link">
                    <i class="bi bi-people" style="color:#0ea5e9;"></i> مساعدين المحتوى
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.countries.index') }}" class="nav-link">
                    <i class="bi bi-geo-alt" style="color:#22c55e;"></i> اداره الدول
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.blogs.index') }}" class="nav-link">
                    <i class="bi bi-newspaper" style="color:#fb7185;"></i> المدونات
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.user_devices.index') }}"
                    class="nav-link {{ request()->routeIs('admin.user_devices.*') ? 'active' : '' }}">
                    <i class="bi bi-phone" style="color:#0ea5e9;"></i>
                    أجهزة المستخدمين
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.apps.index') }}"
                    class="nav-link {{ request()->is('admin/apps*') ? 'active' : '' }}">
                    <i class="bi bi-phone-fill" style="color:#0ea5e9;"></i> إدارة التطبيقات
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.app_features.index') }}"
                    class="nav-link {{ request()->is('app_features*') ? 'active' : '' }}">
                    <i class="bi bi-star" style="color:#f59e42;"></i>
                    مميّزات التطبيق
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.app_contents.index') }}"
                    class="nav-link {{ request()->is('app_contents*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-richtext" style="color:#6366f1;"></i>
                    محتوى التطبيق
                </a>
            </div>
        </div>
    </nav>
</aside>
