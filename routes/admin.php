<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Admin\CollegeController;
use App\Http\Controllers\Admin\MajorController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\AssetController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\DisciplineController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\AcademicCalendarController;
use App\Http\Controllers\Admin\AcademicTermController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\BannersController;
use App\Http\Controllers\Admin\StudentRequestsController;
use App\Http\Controllers\Admin\ComplaintController;
use App\Http\Controllers\Admin\NotificationsController as AdminNotificationsController;
use App\Http\Controllers\Admin\MajorProgramController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\PlanFeatureController;
use App\Http\Controllers\Admin\ActivationCodesController;
use App\Http\Controllers\Admin\ActivationCodeBatchesController;
use App\Http\Controllers\Admin\PublicCollegeController;
use App\Http\Controllers\Admin\PublicMajorController;
use App\Http\Controllers\Admin\UniversityBranchController;
use App\Http\Controllers\Admin\MedDeviceController;
use App\Http\Controllers\Admin\MedSubjectController;
use App\Http\Controllers\Admin\MedTopicController;
use App\Http\Controllers\Admin\MedDoctorController;
use App\Http\Controllers\Admin\MedVideoController;
use App\Http\Controllers\Admin\ClinicalSubjectController;
use App\Http\Controllers\Admin\MedResourceCategoryController;
use App\Http\Controllers\Admin\MedResourceController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\MedicalYearController;
use App\Http\Controllers\Admin\MedicalTermController;
use App\Http\Controllers\Admin\MedicalSubjectController;
use App\Http\Controllers\Admin\MedicalSystemController;
use App\Http\Controllers\Admin\MedicalSystemSubjectController;
use App\Http\Controllers\Admin\MedicalSubjectContentController;
use App\Http\Controllers\Admin\MedicalContentController;
use App\Http\Controllers\Admin\MedicalCourseController;
use App\Http\Controllers\Admin\ContentAssistantController;
use App\Http\Controllers\Admin\ActivationCodeBatchesExcelExportController;
use App\Http\Controllers\Admin\UserDeviceController;
use App\Http\Controllers\Admin\AppFeatureController;
use App\Http\Controllers\Admin\AppContentController;
use App\Http\Controllers\Admin\AndroidAppController;
use App\Http\Controllers\Admin\AndroidAppReleaseController;
use App\Http\Controllers\Admin\ClinicalSubjectPdfController;
use App\Http\Controllers\Admin\StudyGuideController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ContentImportController;
use App\Http\Controllers\Admin\ActivityButtonController;
use App\Http\Controllers\Admin\ActivityVideoController;
use App\Http\Controllers\Admin\MedicalTipController;
use App\Http\Controllers\Admin\ResearchPdfController;
use App\Http\Controllers\Admin\PracticePdfController;
/*
|--------------------------------------------------------------------------
| Admin Routes (prefix=admin, name=admin.) via RouteServiceProvider
|--------------------------------------------------------------------------
*/

Route::resource('app_contents', AppContentController::class)->except(['show']);

Route::get('universities-management', function () {
    return view('admin.universities_management');
})->name('universities_management');
// ضيوف الأدمن
Route::middleware('guest:admin')->group(function () {
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
});



// تصدير الأكواد إلى Excel
Route::get('activation-code-batches/{batch}/export-excel', [ActivationCodeBatchesExcelExportController::class, 'exportExcel'])
    ->name('activation_code_batches.export_excel');

// صفحة التحكم في قالب Excel والمعاينة
Route::get('activation-code-batches/{batch}/excel-template', [ActivationCodeBatchesExcelExportController::class, 'template'])
    ->name('activation_code_batches.excel_template');
// منطقة الإدارة (محميّة)
Route::middleware('auth:admin')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // CRUD الموارد الأساسية
    Route::resource('users',         UserController::class);
    Route::resource('contents',      ContentController::class)->except(['show']);
    Route::resource('universities',  UniversityController::class)->except(['show']);
    Route::resource('branches',      UniversityBranchController::class)->except(['show']);
    Route::resource('colleges',      CollegeController::class)->except(['show']);
    Route::resource('majors',        MajorController::class)->except(['show']);
    Route::resource('doctors',       DoctorController::class)->except(['show']);
    Route::resource('materials',     MaterialController::class)->except(['show']);
    Route::resource('devices',       DeviceController::class)->except(['show']);
    Route::resource('assets',        AssetController::class)->except(['show']);
    Route::resource('blogs',         BlogController::class)->except(['show']);
    Route::resource('subscriptions', SubscriptionController::class)->except(['show']);
    Route::resource('public-colleges', PublicCollegeController::class)->except(['show']);
    Route::resource('public-majors', PublicMajorController::class)->except(['show']);
    // Medical Education
    // Devices
    Route::resource('med_devices', MedDeviceController::class)
        ->parameters(['med_devices' => 'device'])
        ->except(['show']);

    // Subjects
    Route::resource('med_subjects', MedSubjectController::class)
        ->parameters(['med_subjects' => 'subject'])
        ->except(['show']);

    // Topics
    Route::resource('med_topics', MedTopicController::class)
        ->parameters(['med_topics' => 'topic'])
        ->except(['show']);

    // Doctors
    Route::resource('med_doctors', MedDoctorController::class)
        ->parameters(['med_doctors' => 'doctor'])
        ->except(['show']);

    // Videos
    Route::resource('med_videos', MedVideoController::class)
        ->parameters(['med_videos' => 'video'])
        ->except(['show']);

    // Resource Categories
    Route::resource('med_resource-categories', MedResourceCategoryController::class)
        ->parameters(['med_resource-categories' => 'resource_category'])
        ->except(['show']);


    // Resources (PDFs)
    Route::resource('med_resources', MedResourceController::class)
        ->parameters(['med_resources' => 'resource'])
        ->except(['show']);


    // (اختياري) مسار Ajax لجلب مواضيع مادة معينة للفورمات الديناميكية:
    Route::get('subjects/{subject}/topics', [MedTopicController::class, 'bySubject'])->name('subjects.topics');
    // الموارد المضافة: الدول/التخصصات/البرامج/التقويمات/الفصول
    Route::resource('countries',          CountryController::class)->except(['show']);
    Route::resource('disciplines',        DisciplineController::class)->except(['show']);
    Route::resource('programs',           ProgramController::class)->except(['show']);
    Route::resource('academic-calendars', AcademicCalendarController::class)->except(['show']);
    Route::resource('academic-terms',     AcademicTermController::class)->except(['show']);

    /*
     |-----------------------------
     | الخطط ومزايا الخطط
     |-----------------------------
     */
    Route::resource('plans', PlanController::class)->except(['show']);

    Route::resource('plans.features', PlanFeatureController::class)
        ->except(['show'])
        ->names('plan_features'); // => admin.plan_features.*

    /*
     |-----------------------------
     | الربط Major ↔ Program (Pivot)
     |-----------------------------
     */
    Route::resource('major-programs', MajorProgramController::class)
        ->except(['show'])
        ->names('major_program'); // admin.major_program.*

    /*
     |-----------------------------
     | أكواد التفعيل والدفعات
     |-----------------------------
     */
    Route::resource('activation-code-batches', ActivationCodeBatchesController::class)
        ->names('activation_code_batches');

    Route::get('activation-code-batches/{batch}/export', [ActivationCodeBatchesController::class, 'export'])
        ->name('activation_code_batches.export');

    Route::post('activation-code-batches/{batch}/disable', [ActivationCodeBatchesController::class, 'disable'])
        ->name('activation_code_batches.disable');
    // 1) ضع هذين المسارين أولاً
    Route::get(
        'activation-codes/redeem-form',
        [ActivationCodesController::class, 'redeemForm']
    )->name('activation_codes.redeem_form');

    Route::post(
        'activation-codes/redeem',
        [ActivationCodesController::class, 'redeem']
    )->name('activation_codes.redeem');

    // 2) ثم عرّف الـresource بدون show
    Route::resource('activation-codes', ActivationCodesController::class)
        ->except(['show'])
        ->names('activation_codes');

    /*
     |-----------------------------
     | الثيمات 
     |-----------------------------
     */
    Route::get('/themes',               [ThemeController::class, 'index'])->name('themes.index');
    Route::get('/themes/{university}/edit', [ThemeController::class, 'edit'])->name('themes.edit');
    Route::put('/themes/{university}',      [ThemeController::class, 'update'])->name('themes.update');

    // Banners
    Route::resource('banners', BannerController::class);
    // صفحة الملف الشخصي للمشرف
    Route::get('profile', function () {
        return view('admin.profile.edit');
    })->name('profile');
    Route::post('profile', [AdminAuthController::class, 'updateProfile'])->name('profile.update');
    // إعدادات لوحة التحكم
    Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Student Requests
    Route::get('requests',              [StudentRequestsController::class, 'index'])->name('requests.index');
    Route::get('requests/{id}',         [StudentRequestsController::class, 'show'])->name('requests.show');
    Route::put('requests/{id}/assign',  [StudentRequestsController::class, 'assign'])->name('requests.assign');
    Route::put('requests/{id}/status',  [StudentRequestsController::class, 'changeStatus'])->name('requests.status');
    Route::put('requests/{id}/close',   [StudentRequestsController::class, 'close'])->name('requests.close');
    Route::delete('requests/{id}',      [StudentRequestsController::class, 'destroy'])->name('requests.destroy');

    // Complaints
    Route::get('complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::patch('complaints/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
    Route::delete('complaints/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');
    // Notifications (لوحة إرسال/إدارة إشعارات)
    // CRUD
    Route::get('/notifications',            [AdminNotificationsController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create',     [AdminNotificationsController::class, 'create'])->name('notifications.create');
    Route::post('/notifications',            [AdminNotificationsController::class, 'store'])->name('notifications.store');
    Route::get('/notifications/{id}',       [AdminNotificationsController::class, 'show'])->name('notifications.show');
    Route::delete('/notifications/{id}',       [AdminNotificationsController::class, 'destroy'])->name('notifications.destroy');

    // AJAX options for cascading selects
    Route::get('/notifications/options/users',        [AdminNotificationsController::class, 'optionsUsers'])->name('notifications.options.users');
    Route::get('/notifications/options/universities', [AdminNotificationsController::class, 'optionsUniversities'])->name('notifications.options.universities');
    Route::get('/notifications/options/colleges',     [AdminNotificationsController::class, 'optionsColleges'])->name('notifications.options.colleges');     // ?university_id=
    Route::get('/notifications/options/majors',       [AdminNotificationsController::class, 'optionsMajors'])->name('notifications.options.majors');       // ?college_id

    // موارد كاملة
    Route::resource('medical_years', MedicalYearController::class)->names('medical_years');
    Route::resource('medical_terms', MedicalTermController::class)->names('medical_terms');
    Route::resource('medical_subjects', MedicalSubjectController::class)->names('medical_subjects');
    Route::resource('medical_systems', MedicalSystemController::class)->names('medical_systems');

    // Study Guides (كيفية مذاكرة وتنظيم الوقت)
    Route::resource('study_guides', StudyGuideController::class)
        ->except(['show'])
        ->names('study_guides');

    // ربط الأنظمة بالمواد (store/destroy فقط + index بسيط)
    Route::get('medical_system_subjects', [MedicalSystemSubjectController::class, 'index'])->name('medical_system_subjects.index');
    Route::post('medical_system_subjects', [MedicalSystemSubjectController::class, 'store'])->name('medical_system_subjects.store');
    Route::delete('medical_system_subjects/{medical_system_subject}', [MedicalSystemSubjectController::class, 'destroy'])->name('medical_system_subjects.destroy');

    // ربط محتوى المواد الخاصة
    Route::get('medical_subject_contents', [MedicalSubjectContentController::class, 'index'])->name('medical_subject_contents.index');
    Route::post('medical_subject_contents', [MedicalSubjectContentController::class, 'store'])->name('medical_subject_contents.store');
    Route::delete('medical_subject_contents/{medical_subject_content}', [MedicalSubjectContentController::class, 'destroy'])->name('medical_subject_contents.destroy');

    // مساعد بحث المحتويات المؤهلة للربط
    Route::get('medical_subject_contents/search-eligible', [MedicalSubjectContentController::class, 'searchEligibleContents'])
        ->name('medical_subject_contents.search');
    // ربط "المحتوى الطبي (خاص)"
    Route::resource('medical_contents', MedicalContentController::class)
        ->except(['show'])
        ->names('medical_contents');
    Route::resource('courses', MedicalCourseController::class)
        ->except(['show'])
        ->names('courses');
    // Activity Buttons / الدورات والأنشطة
    Route::resource('activity_buttons', ActivityButtonController::class)
        ->except(['show']);
    // Medical Tips (نصائح طبية) - شاشة إدارة قائمة الفيديوهات/النصائح
    Route::resource('medical_tips', MedicalTipController::class)->except(['show']);
    // Videos nested under activity buttons (CRUD)
    Route::resource('activity_buttons.videos', ActivityVideoController::class)
        ->shallow()
        ->except(['show'])
        ->names([
            'index' => 'activity_buttons.videos.index',
            'create' => 'activity_buttons.videos.create',
            'store' => 'activity_buttons.videos.store',
            // shallow names
            'edit' => 'activity_videos.edit',
            'update' => 'activity_videos.update',
            'destroy' => 'activity_videos.destroy',
        ]);
    Route::resource('content_assistants', ContentAssistantController::class)->except(['show']);

    // User Devices
    Route::resource('user-devices', UserDeviceController::class)
        ->only(['index', 'destroy'])
        ->names('user_devices');

    /*
     |-----------------------------
     | Import / Excel upload screens
     |-----------------------------
     */
    Route::get('imports', [ImportController::class, 'index'])->name('imports.index');
    Route::get('imports/{type}', [ImportController::class, 'show'])->name('imports.show');
    Route::post('imports/{type}', [ImportController::class, 'upload'])->name('imports.upload');
    Route::post('imports/{type}/preview', [ImportController::class, 'preview'])->name('imports.preview');
    Route::post('imports/{type}/confirm', [ImportController::class, 'confirm'])->name('imports.confirm');
    Route::get('imports/{type}/errors-export', [ImportController::class, 'errorsExport'])->name('imports.errors_export');
    Route::get('imports/{type}/template', [ImportController::class, 'template'])->name('imports.template');

    // Separate content imports (videos, resources, clinical PDFs)
    Route::get('content-imports', [ContentImportController::class, 'index'])->name('content_imports.index');
    Route::get('content-imports/{type}', [ContentImportController::class, 'show'])->name('content_imports.show');
    Route::post('content-imports/{type}', [ContentImportController::class, 'upload'])->name('content_imports.upload');
    Route::post('content-imports/{type}/preview', [ContentImportController::class, 'preview'])->name('content_imports.preview');
    Route::post('content-imports/{type}/confirm', [ContentImportController::class, 'confirm'])->name('content_imports.confirm');
    Route::get('content-imports/{type}/errors-export', [ContentImportController::class, 'errorsExport'])->name('content_imports.errors_export');
    Route::get('content-imports/{type}/template', [ContentImportController::class, 'template'])->name('content_imports.template');

    // مميزات التطبيق
    Route::resource('app_features', AppFeatureController::class)->except(['show']);
    // إدارة تطبيقات الأندرويد (صفحة التطبيق في الواجهة + تحميل apk)
    Route::resource('apps', AndroidAppController::class)->except(['show']);
    // Releases nested resource for app (manage updates)
    Route::resource('apps.releases', AndroidAppReleaseController::class)->shallow()->except(['show','edit','update']);
    Route::resource('clinical_subjects', ClinicalSubjectController::class)->names([
    'index' => 'clinical_subjects.index',
    'create' => 'clinical_subjects.create',
    'store' => 'clinical_subjects.store',
    'edit' => 'clinical_subjects.edit',
    'update' => 'clinical_subjects.update',
    'destroy' => 'clinical_subjects.destroy',
]);
Route::resource('clinical_subject_pdfs', ClinicalSubjectPdfController::class)->names([
    'index' => 'clinical_subject_pdfs.index',
    'create' => 'clinical_subject_pdfs.create',
    'store' => 'clinical_subject_pdfs.store',
    'edit' => 'clinical_subject_pdfs.edit',
    'update' => 'clinical_subject_pdfs.update',
    'destroy' => 'clinical_subject_pdfs.destroy',
]);
    // Practice PDFs (اختبار مزاولة المهنة) - شاشة إدارة ملفات PDF مستقلة
    Route::resource('practice_pdfs', PracticePdfController::class)->names([
        'index' => 'practice_pdfs.index',
        'create' => 'practice_pdfs.create',
        'store' => 'practice_pdfs.store',
        'edit' => 'practice_pdfs.edit',
        'update' => 'practice_pdfs.update',
        'destroy' => 'practice_pdfs.destroy',
    ]);
    // Research PDFs (الأبحاث العلمية ورسائل الماجستير)
    Route::resource('research_pdfs', ResearchPdfController::class)->names([
        'index' => 'research_pdfs.index',
        'create' => 'research_pdfs.create',
        'store' => 'research_pdfs.store',
        'edit' => 'research_pdfs.edit',
        'update' => 'research_pdfs.update',
        'destroy' => 'research_pdfs.destroy',
    ]);
});
