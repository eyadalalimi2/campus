<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware stack.
     * تُنفَّذ على كل طلب.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        // ميدلوير Sanctum للـ SPA لا نحتاجه عالميًا في تدفّق Bearer للموبايل
        // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ];

    /**
     * Route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // إن كان لديك SPA stateful على نفس الدومين فعّل السطر التالي داخل web لا global:
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ],

        'api' => [
            // فرض الاستجابة JSON لكل مسارات API
            \App\Http\Middleware\ForceJsonResponse::class,
            // تحديد المعدّل المعرّف باسم api في RouteServiceProvider
            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Middleware aliases.
     * تُستخدم للأسماء المختصرة داخل routes.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth'             => \App\Http\Middleware\Authenticate::class,
        'auth.basic'       => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session'     => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers'    => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can'              => \Illuminate\Auth\Middleware\Authorize::class,
        'guest'            => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed'           => \App\Http\Middleware\ValidateSignature::class,
        'throttle'         => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified'         => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // ميدلويرنا المخصصة
        'force.json'       => \App\Http\Middleware\ForceJsonResponse::class,
        'verified.api'     => \App\Http\Middleware\EnsureEmailIsVerifiedForApi::class,
    ];
}
