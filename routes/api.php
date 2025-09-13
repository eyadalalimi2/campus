<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Me\ProfileController;
use App\Http\Controllers\Api\V1\Me\SecurityController;
use App\Http\Controllers\Api\V1\Me\VisibilityController;

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

use App\Http\Controllers\Api\V1\Feed\FeedController;
use App\Http\Controllers\Api\V1\Me\DevicesController;

Route::prefix('v1')->group(function () {

    /* =========================
     * Auth (بدون مصادقة)
     * ========================= */

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


        /* إدارة الأجهزة/التوكنات (Sanctum) */
        Route::get('me/devices', [DevicesController::class, 'index']);
        // قائمة التوكنات الصادرة (اسم الجهاز/الصلاحيات/آخر استخدام)

        Route::delete('me/devices/{tokenId}', [DevicesController::class, 'destroy']);
        // إلغاء/حذف توكن جهاز محدد يخص المستخدم الحالي
    });
});
