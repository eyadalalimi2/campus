<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * المسار الافتراضي بعد تسجيل الدخول (كما كان لديك سابقًا).
     */
    public const HOME = '/dashboard';

    /**
     * ملاحظة: لا نُعرّف register() هنا لتفادي أي اختلاف عن السلوك الافتراضي.
     */

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // نضبط محددات المعدّل فقط — لا شيء آخر يتغير.
        $this->configureRateLimiting();

        // نحافظ تمامًا على نفس ترتيب وتعريف مجموعات المسارات التي كانت تعمل عندك.
        $this->routes(function () {
            // web routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // api routes
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // admin routes (كما كان لديك: prefix + as)
            Route::middleware('web')
                ->prefix('admin')
                ->as('admin.')
                ->group(base_path('routes/admin.php'));
        });
    }

    /**
     * تهيئة محددات الاستهلاك (Rate Limiting).
     */
    protected function configureRateLimiting(): void
    {
        // عام لمسارات API
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // تسجيل الدخول: 5 محاولات/دقيقة لكل (email + IP)
        RateLimiter::for('login', function (Request $request) {
            $email = strtolower((string) $request->input('email', ''));
            return Limit::perMinute(5)->by($email . '|' . $request->ip());
        });

        // إرسال بريد "نسيت كلمة المرور": 3 محاولات/5 دقائق لكل (email + IP)
        RateLimiter::for('password-email', function (Request $request) {
            $email = strtolower((string) $request->input('email', ''));
            return Limit::perMinutes(5, 3)->by($email . '|' . $request->ip());
        });

        // إعادة إرسال رابط التحقق من البريد: 3 محاولات/5 دقائق لكل مستخدم
        RateLimiter::for('verification-email', function (Request $request) {
            $key = $request->user()?->id ?: $request->ip();
            return Limit::perMinutes(5, 3)->by('verify:' . $key);
        });
    }
}
