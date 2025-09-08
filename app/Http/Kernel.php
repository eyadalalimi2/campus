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
        // ميدلوير Sanctum الخاص بالـ SPA غير مطلوب عالميًا في سيناريو الموبايل (Bearer Token)
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
            // إن كان لديك SPA stateful على نفس الدومين فعِّل التالي داخل web فقط:
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ],

        'api' => [
            // إجبار JSON + تعريب حسب الهيدر
            \App\Http\Middleware\Api\ForceJson::class,
            \App\Http\Middleware\Api\ApiLocale::class,

            // ثروتل افتراضي لمجموعة API
            'throttle:api',

            // ربط معرّفات الراوت بالموديلات (bindings)
            \Illuminate\Routing\Middleware\SubstituteBindings::class,

            // Idempotency لحماية POST/PUT/PATCH/DELETE (اختياري لكنه موصى به)
            \App\Http\Middleware\Api\Idempotency::class,

            // فرض نطاق المستخدم للمحتوى الخاص (يمكن إبقاؤه هنا أو إضافته انتقائيًا على روتات معيّنة)
            \App\Http\Middleware\Api\UserScopeEnforcer::class,

            // ملاحظة: لا نستخدم EnsureFrontendRequestsAreStateful في مجموعة API الخاصة بالموبايل.
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

        // ميدلويراتنا المخصصة (أسماء مختصرة للاستخدام في الراوت)
        'force.json'       => \App\Http\Middleware\Api\ForceJson::class,
        'abilities'        => \App\Http\Middleware\Api\CheckAbilities::class,
        'idem'             => \App\Http\Middleware\Api\Idempotency::class,
        'u-scope'          => \App\Http\Middleware\Api\UserScopeEnforcer::class,
        'verified.api'     => \App\Http\Middleware\EnsureEmailIsVerifiedForApi::class,
    ];
}
