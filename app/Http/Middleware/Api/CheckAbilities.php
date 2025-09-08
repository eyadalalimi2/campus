<?php
namespace App\Http\Middleware\Api;

use Closure;
use App\Support\ApiResponse;

class CheckAbilities
{
    public function handle($request, Closure $next, ...$abilities)
    {
        $user = $request->user();
        if (!$user) return ApiResponse::error('UNAUTHENTICATED', 'يجب تسجيل الدخول.', [], 401);

        $token = $user->currentAccessToken();
        if (!$token) return ApiResponse::error('TOKEN_MISSING', 'التوكن غير موجود.', [], 401);

        $has = collect($abilities)->every(fn($a) => $token->can($a));
        if (!$has) return ApiResponse::error('FORBIDDEN', 'ليست لديك صلاحية لهذه العملية.', [], 403);

        return $next($request);
    }
}
