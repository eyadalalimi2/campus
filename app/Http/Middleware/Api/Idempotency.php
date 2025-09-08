<?php
namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Support\Facades\Cache;
use App\Support\ApiResponse;

class Idempotency
{
    public function handle($request, Closure $next)
    {
        $key = $request->header('Idempotency-Key');
        if (!$key) return $next($request);

        $cacheKey = 'idem:' . sha1($key . '|' . $request->user()->id);
        if (Cache::has($cacheKey)) {
            return ApiResponse::error('IDEMPOTENT_REPLAY', 'تمت معالجة هذا الطلب مسبقًا.', [], 409);
        }

        $response = $next($request);
        Cache::put($cacheKey, 1, now()->addDay());
        return $response;
    }
}
