<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\V1\MedicalPrivateController;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Me\ProfileController;
use App\Http\Controllers\Api\V1\Me\SecurityController;
use App\Http\Controllers\Api\V1\Me\VisibilityController;
use App\Http\Controllers\Api\V1\Structure\PublicTaxonomyController;
use App\Http\Controllers\Api\V1\Structure\CountriesController;
use App\Http\Controllers\Api\V1\Structure\UniversitiesController;
use App\Http\Controllers\Api\V1\Structure\CollegesController;
use App\Http\Controllers\Api\V1\Structure\MajorsController;

use App\Http\Controllers\Api\V1\Academic\CalendarsController;
use App\Http\Controllers\Api\V1\Academic\TermsController;

use App\Http\Controllers\Api\V1\Catalog\MaterialsController;

use App\Http\Controllers\Api\V1\Assets\AssetsController;
use App\Http\Controllers\Api\V1\Content\ContentsController;

use App\Http\Controllers\Api\V1\Plans\PlansController;
use App\Http\Controllers\Api\V1\Plans\FeaturesController;

use App\Http\Controllers\Api\V1\Subscription\ActivationController;
use App\Http\Controllers\Api\V1\Subscription\SubscriptionsController;

use App\Http\Controllers\MedicalSystemController;
use App\Http\Controllers\Api\V1\Feed\FeedController;
use App\Http\Controllers\Api\V1\UserDeviceController;
use App\Http\Controllers\Api\V1\BannerController;
use App\Http\Controllers\Api\V1\NotificationsController;
use App\Http\Controllers\Api\V1\StudentRequestsController;
use App\Http\Controllers\Api\V1\ComplaintController;
use App\Http\Controllers\Api\V1\Me\VisibilityController as ApiVisibilityController;
use App\Http\Controllers\Api\V1\ContentAssistantController as ApiContentAssistantController;
use App\Http\Controllers\Api\V1\AppFeaturesController;
use App\Http\Controllers\Api\V1\AppContentsController;
use App\Http\Controllers\Api\V1\ActivityButtonsController;
use App\Http\Controllers\Api\V1\ClinicalSubjectPdfController as ApiClinicalSubjectPdfController;
use App\Http\Controllers\Api\V1\ClinicalSubjectController;
use App\Http\Controllers\Api\V1\StudyGuideController as ApiStudyGuideController;
use App\Http\Controllers\Api\V1\{
    MedDeviceController,
    MedSubjectController,
    MedTopicController,
    MedDoctorController,
    MedVideoController,
    MedResourceController,
};

Route::prefix('v1')->group(function () {
    // Clinical Subjects
    Route::get('clinical-subjects', [ClinicalSubjectController::class, 'index']);
    // Clinical Subject PDFs
    Route::get('clinical-subjects/{clinical_subject}/pdfs', [ApiClinicalSubjectPdfController::class, 'index']);

    //content-assistants
    Route::get('content-assistants', [ApiContentAssistantController::class, 'index']);

    // Study Guides (public)
    Route::get('study-guides', [ApiStudyGuideController::class, 'index']);
    Route::get('study-guides/{study_guide}', [ApiStudyGuideController::class, 'show']);

    // Courses
    Route::get('courses', [\App\Http\Controllers\Api\V1\CourseController::class, 'index']);

    // University Branches (by university)
    Route::get('universities/{id}/branches', [\App\Http\Controllers\Api\V1\UniversityBranchController::class, 'byUniversity']);
    // Branch Colleges (by branch)
    Route::get('branches/{branch_id}/colleges', [\App\Http\Controllers\Api\V1\Structure\BranchCollegesController::class, 'byBranch']);


    /* =========================
     * Auth (بدون مصادقة)
     * ========================= */


    //المحتوى الطبي التعليمي العام
    // Devices
    Route::get('devices', [MedDeviceController::class, 'index']);
    Route::get('devices/{device}/subjects', [MedSubjectController::class, 'byDevice']);
    //App Features
    Route::get('app-features', [AppFeaturesController::class, 'index']);
    //App Contents
    Route::get('app-contents', [AppContentsController::class, 'index']);
    // Activity Buttons (الدورات والأنشطة) - قائمة الأزرار المتوفرة في التطبيق
    Route::get('activity-buttons', [ActivityButtonsController::class, 'index']);
    // قائمة الفيديوهات الخاصة بزر معين
    Route::get('activity-buttons/{button}/videos', [ActivityButtonsController::class, 'videos']);
    // Subjects
    Route::get('subjects', [MedSubjectController::class, 'index']);
    Route::get('subjects/{subject}/topics', [MedTopicController::class, 'bySubject']);
    Route::get('subjects/{subject}/doctors', [MedDoctorController::class, 'bySubject']);
    Route::get('subjects/{subject}/videos', [MedVideoController::class, 'bySubject']);
    Route::get('subjects/{subject}/resources', [MedResourceController::class, 'bySubject']);

    // Topics
    Route::get('topics/{topic}/videos', [MedVideoController::class, 'byTopic']);
    Route::get('topics/{topic}/resources', [MedResourceController::class, 'byTopic']);

    // Doctors
    Route::get('doctors', [MedDoctorController::class, 'index']);
    Route::get('doctors/{doctor}/videos', [MedVideoController::class, 'byDoctor']);

    // Videos
    Route::get('videos', [MedVideoController::class, 'index']);

    // Resources (PDFs)
    Route::get('resources', [MedResourceController::class, 'index']);
    // عام: البنرات
    Route::get('banners', [BannerController::class, 'index']);

    Route::post('auth/register', [AuthController::class, 'register']);
    // إنشاء حساب جديد وإرجاع توكن جلسة أولية

    Route::post('auth/login', [AuthController::class, 'login']);
    // تسجيل الدخول عبر (email/password + login_device) وإصدار توكن Sanctum


    /* =========================
     * Email verification (بدون توكن)
     * ========================= */

    // إرسال رابط التفعيل
    Route::post('auth/email/resend', [AuthController::class, 'resendEmailVerificationLink']);

    // تفعيل عبر الرابط (GET) بالـ token
    Route::get('auth/email/verify/{token}', [AuthController::class, 'verifyEmailByToken'])
        ->name('api.v1.auth.email.verify');


    /* =========================
     * Password reset (بدون توكن)
     * ========================= */

    Route::post('auth/password/forgot', [AuthController::class, 'forgotPassword']);
    // إرسال كود/رابط استعادة كلمة المرور إلى البريد

    Route::post('auth/password/reset', [AuthController::class, 'resetPassword']);
    // تعيين كلمة مرور جديدة (email + token + password_confirmation)


    /* =========================
     * مرجعية عامة (قراءة عامة)
     * ========================= */

    Route::get('countries', [CountriesController::class, 'index']);
    // قائمة الدول

    Route::get('universities', [UniversitiesController::class, 'index']);
    // قائمة الجامعات (مع إمكانية ترشيح/ترتيب حسب ما يدعمه الكنترولر)

    Route::get('universities/{id}/colleges', [CollegesController::class, 'byUniversity']);
    // كليات جامعة محددة

    Route::get('colleges/{id}/majors', [MajorsController::class, 'byCollege']);
    // تخصصات كلية محددة

    Route::get('calendars', [CalendarsController::class, 'index']);
    // التقويمات الأكاديمية (مع دعم is_active والجامعة)

    Route::get('calendars/{id}/terms', [TermsController::class, 'byCalendar']);
    // الفصول/الفترات الأكاديمية لتقويم محدد

    Route::get('materials', [MaterialsController::class, 'index']);
    // مواد/مقررات الكتالوج (عامة/مؤسسية)

    Route::get('materials/{id}', [MaterialsController::class, 'show']);
    // تفاصيل مادة واحدة


    /* =========================
     * مسارات عامة للأصول (Assets) — مرئية للجميع
     * ========================= */
    Route::get('public/taxonomy', [PublicTaxonomyController::class, 'index']);
    Route::get('assets', [AssetsController::class, 'index']);
    // تغذية الأصول العامة (يوتيوب/ملفات/مراجع...) مع استهداف الجمهور حسب التخصص إذا توفّر

    Route::get('assets/{id}', [AssetsController::class, 'show']);
    // تفاصيل أصل عام واحد


    /* =========================
     * مسارات محمية بالتوكن
     * ========================= */

    Route::middleware([
        'auth:sanctum',
        // السماح بقراءة هيكل المؤسسة والكتالوج والـ Me
        'abilities:structure:read,catalog:read,me:read',
    ])->group(function () {

        /* Auth/Me */
        Route::get('auth/me', [AuthController::class, 'me']);
        // معلومات الحساب الحالي + الصلاحيات

        Route::post('auth/logout', [AuthController::class, 'logout']);
        // إبطال التوكن الحالي
        Route::delete('me/account', [AuthController::class, 'destroyAccount'])
            ->middleware('idem');

        /* الملف الشخصي */
        Route::get('me/profile', [ProfileController::class, 'show']);
        // قراءة بيانات الملف الشخصي

        Route::put('me/profile', [ProfileController::class, 'update']);
        // تحديث بيانات الملف الشخصي

        Route::post('me/profile/photo', [ProfileController::class, 'uploadPhoto']);
        // رفع/تحديث صورة البروفايل

        Route::put('me/security/change-password', [SecurityController::class, 'changePassword']);
        // تغيير كلمة المرور (التحقق من الحالية + تعيين جديدة)

        Route::get('me/visibility', [VisibilityController::class, 'show']);
        // يوضح إن كان المستخدم مرتبطًا بجامعة وما المصادر المتاحة (assets فقط أو assets+contents)


        /* Feed (موحَّد) */
        Route::get('me/feed', [FeedController::class, 'index'])->name('api.v1.feed.index');
        // دمج أحدث الأصول العامة + المحتوى المؤسسي المسموح وفق نطاق المستخدم (cursor/limit مدعومان)


        /* محتوى مؤسسي (خاص) — يتطلب ارتباط بجامعة */
        Route::middleware('u-scope')->group(function () {
            Route::get('contents', [ContentsController::class, 'index']);
            // قائمة المحتوى الخاص (university/college/major) للمستخدم المرتبط فقط

            Route::get('contents/{id}', [ContentsController::class, 'show']);
            // تفاصيل عنصر محتوى خاص
        });


        /* الخطط والمزايا (قراءة) */
        Route::get('plans', [PlansController::class, 'index']);
        // قائمة الخطط النشطة

        Route::get('plans/{id}', [PlansController::class, 'show']);
        // تفاصيل خطة

        Route::get('plans/{id}/features', [FeaturesController::class, 'byPlan']);
        // مزايا خطة محددة


        /* الاشتراكات */
        Route::get('me/subscription', [SubscriptionsController::class, 'active']);
        // الاشتراك النشط الحالي (إن وجد)

        Route::get('me/subscriptions', [SubscriptionsController::class, 'index']);
        // آخر الاشتراكات الخاصة بالمستخدم (هيستوري مبسّط)

        Route::post('me/activate-code', [ActivationController::class, 'redeem'])
            ->middleware('idem')                       // Idempotency لمنع التفعيل المكرر لنفس الطلب
            ->middleware('abilities:subscription:write');
        // تفعيل كود اشتراك وربطه بالمستخدم


        // جهاز المستخدم
        Route::get('user-devices', [UserDeviceController::class, 'index']);
        // إلغاء/حذف توكن جهاز محدد يخص المستخدم الحالي
        // إشعارات الطالب
        Route::get('me/notifications',          [NotificationsController::class, 'index']);
        Route::get('me/notifications/{id}',     [NotificationsController::class, 'show']);
        Route::patch('me/notifications/{id}/read', [NotificationsController::class, 'markRead']);
        Route::patch('me/notifications/read-all', [NotificationsController::class, 'markAllRead']);
        Route::delete('me/notifications/{id}', [NotificationsController::class, 'destroy']);
        // طلبات الطالب
        Route::get('me/requests',            [StudentRequestsController::class, 'index']);
        Route::post('me/requests',            [StudentRequestsController::class, 'store']);
        Route::get('me/requests/{id}',       [StudentRequestsController::class, 'show']);
        Route::patch('me/requests/{id}',       [StudentRequestsController::class, 'update']);
        Route::delete('me/requests/{id}',       [StudentRequestsController::class, 'destroy']);

        // Complaints
        Route::get('complaints',        [ComplaintController::class, 'index']);   // ?status=&severity=&type=&q=
        Route::post('complaints',        [ComplaintController::class, 'store']);   // form-data أو JSON + ملف
        Route::get('complaints/{id}',   [ComplaintController::class, 'show']);    // يضمن الملكية عبر Policy
        Route::patch('complaints/{id}',   [ComplaintController::class, 'update']);  // تعديل النص/إرفاق/إغلاق
        Route::delete('complaints/{id}',   [ComplaintController::class, 'destroy']); // حذف (Soft) لحالات محددة
        // Visibility (عرض/تحديث)
        Route::get('me/visibility', [ApiVisibilityController::class, 'show']);
        Route::put('me/visibility', [ApiVisibilityController::class, 'update']);
        // هرم السنوات ← الفصول ← المواد
        Route::get('medical/years',       [MedicalPrivateController::class, 'years']);        // ?major_id= (اختياري: إن لم يُمرر نستخدم major الخاص بالمستخدم)
        Route::get('medical/years/{year}/terms', [MedicalPrivateController::class, 'terms']);
        Route::get('medical/terms/{termId}/subjects', [MedicalPrivateController::class, 'subjects']); // ?track=REQUIRED|SYSTEM|CLINICAL

        // الأنظمة حسب السنة + مواد النظام
        Route::get('medical/systems', [MedicalPrivateController::class, 'systems']); // ?year_id=
        Route::get('medical/systems/{system}/subjects', [MedicalPrivateController::class, 'systemSubjects']);
        Route::get(
            'medical/years/{year}/terms/{term}/systems',
            [MedicalSystemController::class, 'systemsByYearAndTerm']
        )
            ->whereNumber('year')
            ->whereNumber('term'); // term_number أو id، سنحله مخصصًا
        // محتوى مادة (يرجع من contents عبر MedicalSubjectContent)
        Route::get('medical/subjects/{subject}/contents', [MedicalPrivateController::class, 'subjectContents']); // ?type=file|link
    });
});
