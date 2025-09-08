<?php
namespace App\Http\Middleware\Api;

use Closure;
use App\Support\ApiResponse;

class UserScopeEnforcer
{
    public function handle($request, Closure $next)
    {
        // يمنع الوصول إلى /contents لغير المرتبطين بجامعة
        if (str_starts_with($request->path(), 'api/v1/contents')) {
            $u = $request->user();
            if (!$u || !$u->university_id) {
                return ApiResponse::error('CONTENTS_FOR_UNIVERSITY_ONLY', 'لا يمكنك عرض محتوى الجامعة لأن حسابك غير مرتبط بجامعة.', [], 403);
            }
        }
        return $next($request);
    }
}
