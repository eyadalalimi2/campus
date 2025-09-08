<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use App\Support\ApiResponse;

final class CheckAbilities
{
    /**
     * يتحقق أن توكن Sanctum يملك جميع الصلاحيات المطلوبة.
     * الاستخدام في المسارات: ->middleware('abilities:structure:read,catalog:read')
     */
    public function handle(Request $request, Closure $next, ...$abilities)
    {
        $user = $request->user();

        if (!$user) {
            return ApiResponse::error('UNAUTHORIZED', 'الرجاء تسجيل الدخول.', [], 401);
        }

        $token = $user->currentAccessToken();
        if (!$token) {
            return ApiResponse::error('FORBIDDEN', 'لا يوجد توكن للوصول.', [], 403);
        }

        // السماح إن كان يمتلك النجمة
        if ($user->tokenCan('*')) {
            return $next($request);
        }

        // يجب امتلاك كل الصلاحيات المطلوبة
        $missing = [];
        foreach ($abilities as $ability) {
            if (!$user->tokenCan($ability)) {
                $missing[] = $ability;
            }
        }

        if ($missing) {
            return ApiResponse::error(
                'INSUFFICIENT_ABILITIES',
                'صلاحيات غير كافية للوصول.',
                ['missing' => $missing],
                403
            );
        }

        return $next($request);
    }
}
