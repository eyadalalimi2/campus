<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use App\Support\ApiResponse;

final class UserScopeEnforcer
{
    /**
     * يمنع الوصول للمحتوى الخاص (university-scoped) لمن لا يملك ربطًا بجامعة.
     * يمكن توسيعه لاحقًا للتحقق من الحالة (suspended/graduated).
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return ApiResponse::error('UNAUTHORIZED', 'الرجاء تسجيل الدخول.', [], 401);
        }

        if (empty($user->university_id)) {
            return ApiResponse::error(
                'UNIVERSITY_SCOPE_REQUIRED',
                'لا يمكنك الوصول إلى هذا المورد لأن حسابك غير مرتبط بجامعة.',
                [],
                403
            );
        }

        // مثال للتحقق من الحالة (اختياري)
        if (method_exists($user, 'getAttribute') && $user->getAttribute('status') === 'suspended') {
            return ApiResponse::error('ACCOUNT_SUSPENDED', 'حسابك موقوف مؤقتًا.', [], 403);
        }

        return $next($request);
    }
}
