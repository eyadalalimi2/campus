<aside class="sidebar p-3">
    <div class="mb-2 text-secondary small">القائمة الرئيسية</div>
    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> لوحة البيانات
    </a>
    <hr class="text-secondary">

    <div class="text-secondary small mb-1">إدارة الجامعات</div>
    <a href="{{ route('admin.universities.index') }}" class="{{ request()->is('admin/universities*') ? 'active' : '' }}">
        <i class="bi bi-building"></i> الجامعات
    </a>
    <a href="{{ route('admin.colleges.index') }}" class="{{ request()->is('admin/colleges*') ? 'active' : '' }}">
        <i class="bi bi-bank"></i> الكليات
    </a>
    <a href="{{ route('admin.majors.index') }}" class="{{ request()->is('admin/majors*') ? 'active' : '' }}">
        <i class="bi bi-diagram-3"></i> التخصصات
    </a>
    <hr class="text-secondary">

    <div class="text-secondary small mb-1">الإعدادات</div>
    <a href="{{ route('admin.import.index') }}" class="{{ request()->is('admin/import*') ? 'active' : '' }}">
        <i class="bi bi-upload"></i> الاستيراد (Excel)
    </a>
    <a href="{{ route('admin.themes.index') }}" class="{{ request()->is('admin/themes*') ? 'active' : '' }}">
        <i class="bi bi-palette"></i> إدارة الثيمات
    </a>
    <a href="{{ route('admin.doctors.index') }}" class="{{ request()->is('admin/doctors*') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i> الدكاترة
    </a>
    <a href="{{ route('admin.contents.index') }}" class="{{ request()->is('admin/contents*') ? 'active' : '' }}">
        <i class="bi bi-folder"></i> المحتوى
    </a>
    <a href="{{ route('admin.materials.index') }}" class="{{ request()->is('admin/materials*') ? 'active' : '' }}">
        <i class="bi bi-journal-text"></i> المواد
    </a>
    <a href="{{ route('admin.devices.index') }}" class="{{ request()->is('admin/devices*') ? 'active' : '' }}">
        <i class="bi bi-cpu"></i> الأجهزة/المهام
    </a>
    <a href="{{ route('admin.assets.index') }}" class="{{ request()->is('admin/assets*') ? 'active' : '' }}">
        <i class="bi bi-collection"></i> العناصر التعليمية
    </a>

</aside>
