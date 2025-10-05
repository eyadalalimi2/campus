<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Admin\CollegeController;
use App\Http\Controllers\Admin\MajorController;
use App\Http\Controllers\Admin\ImportController;
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
/*
|--------------------------------------------------------------------------
| Admin Routes (prefix=admin, name=admin.) via RouteServiceProvider
|--------------------------------------------------------------------------
*/

Route::get('universities-management', function () {
    return view('admin.universities_management');
})->name('universities_management');
// ضيوف الأدمن
Route::middleware('guest:admin')->group(function () {
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
});

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
     | الثيمات + الاستيراد
     |-----------------------------
     */
    Route::get('/themes',               [ThemeController::class, 'index'])->name('themes.index');
    Route::get('/themes/{university}/edit', [ThemeController::class, 'edit'])->name('themes.edit');
    Route::put('/themes/{university}',      [ThemeController::class, 'update'])->name('themes.update');

    Route::get('/import',               [ImportController::class, 'index'])->name('import.index');
    Route::post('/import/run',          [ImportController::class, 'run'])->name('import.run');
    Route::get('/import/sample/{type}', [ImportController::class, 'sample'])->name('import.sample');

    // Banners
    Route::resource('banners', BannerController::class);

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
    Route::resource('content_assistants', ContentAssistantController::class)->except(['show']);
});
