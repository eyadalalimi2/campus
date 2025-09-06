<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use App\Http\Controllers\Api\Profile\ProfileController;
use App\Http\Controllers\Api\Profile\AvatarController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| كل المسارات هنا تُرجع JSON (بفضل ForceJsonResponse في مجموعة api بـ Kernel)
| ومحمية بمحددات المعدّل المُعرّفة في RouteServiceProvider.
*/

/**
 * Auth Routes
 */
Route::prefix('auth')->group(function () {
    // تسجيل حساب جديد
    Route::post('register', RegisterController::class)
        ->name('api.auth.register');

    // تسجيل الدخول (محدّد محاولات: 5/دقيقة لكل بريد+IP)
    Route::post('login', LoginController::class)
        ->middleware('throttle:login')
        ->name('api.auth.login');

    // إعادة إرسال رابط التحقق (يتطلب توكن + محدد محاولات)
    Route::post('email/verification-notification', EmailVerificationNotificationController::class)
        ->middleware(['auth:sanctum', 'throttle:verification-email'])
        ->name('api.auth.verification.send');

    // تفعيل البريد عبر رابط موقّع (يتطلب توكن + signed)
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['auth:sanctum', 'signed'])
        ->name('api.auth.verification.verify');

    // نسيت كلمة المرور (استجابة موحّدة + محدّد محاولات)
    Route::post('forgot-password', ForgotPasswordController::class)
        ->middleware('throttle:password-email')
        ->name('api.auth.password.email');

    // إعادة تعيين كلمة المرور
    Route::post('reset-password', ResetPasswordController::class)
        ->name('api.auth.password.reset');

    // تسجيل الخروج (حذف التوكن الحالي)
    Route::post('logout', LogoutController::class)
        ->middleware('auth:sanctum')
        ->name('api.auth.logout');
});

/**
 * Profile Routes (Protected)
 */
Route::middleware(['auth:sanctum', 'verified.api'])->group(function () {
    // عرض البروفايل
    Route::get('profile', [ProfileController::class, 'show'])
        ->name('api.profile.show');

    // تعديل بيانات البروفايل
    Route::match(['put', 'patch'], 'profile', [ProfileController::class, 'update'])
        ->name('api.profile.update');

    // تغيير كلمة المرور
    Route::match(['put', 'patch'], 'profile/password', [ProfileController::class, 'updatePassword'])
        ->name('api.profile.password.update');

    // الصورة الشخصية: رفع/تحديث
    Route::match(['post', 'put'], 'profile/avatar', [AvatarController::class, 'upsert'])
        ->name('api.profile.avatar.upsert');

    // الصورة الشخصية: حذف
    Route::delete('profile/avatar', [AvatarController::class, 'destroy'])
        ->name('api.profile.avatar.destroy');
});

/**
 * (اختياري) مسار فحص التوكن السريع
 */
Route::middleware('auth:sanctum')->get('/user', function (\Illuminate\Http\Request $request) {
    return $request->user();
})->name('api.user.me');
