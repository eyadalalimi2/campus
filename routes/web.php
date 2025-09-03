<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Site\StudentAuthController;
use App\Http\Controllers\Site\StudentDashboardController;
use App\Http\Controllers\Site\StudentVerificationController;


Route::get('/', function () {
    return view('site.home');
})->name('site.home');
// ضيوف (دخول/تسجيل/نسيت كلمة المرور + صفحة إعادة التعيين بالـ token)
Route::middleware('guest')->group(function () {
    Route::get('/login',  [StudentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])->name('login.post');

    Route::get('/register',  [StudentAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [StudentAuthController::class, 'register'])->name('register.post');

    Route::get('/password/forgot', [StudentAuthController::class, 'showForgot'])->name('password.request');
    Route::post('/password/email',  [StudentAuthController::class, 'sendResetLink'])->name('password.email');

    // صفحة إعادة التعيين عبر الرابط (token)
    Route::get('/password/reset/{token}', [StudentAuthController::class, 'showReset'])->name('password.reset');
    Route::post('/password/reset',          [StudentAuthController::class, 'reset'])->name('password.update');
});

// خروج
Route::post('/logout', [StudentAuthController::class, 'logout'])->middleware('auth')->name('logout');

// التحقّق بالبريد (Email Verification)
Route::prefix('email')->middleware('auth')->group(function () {
    // صفحة إشعار “تحقّق من بريدك”
    Route::get('/verify', [StudentVerificationController::class, 'notice'])->name('verification.notice');

    // رابط التفعيل الموقّع
    Route::get('/verify/{id}/{hash}', [StudentVerificationController::class, 'verify'])
        ->middleware('signed')->name('verification.verify');

    // إعادة إرسال رسالة التفعيل
    Route::post('/verification-notification', [StudentVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')->name('verification.send');
});

// لوحة تحكم الطالب — تتطلب بريدًا مفعّلًا
Route::prefix('student')->middleware(['auth','verified'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
});