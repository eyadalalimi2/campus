<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

final class Idempotency
{
    /**
     * يدعم الطلبات غير الآمنة (POST/PUT/PATCH/DELETE) عند وجود الترويسة:
     *   Idempotency-Key: <string>
     *
     * يُخزّن الرد JSON (الحالة/الجسم وبعض الترويسات) مدة config('api.idempotency.ttl_hours', 24).
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->isUnsafe($request)) {
            return $next($request);
        }

        $key = trim((string)$request->headers->get('Idempotency-Key', ''));
        if ($key === '') {
            return $next($request);
        }

        $cacheKey = $this->makeCacheKey($request, $key);
        if ($cached = Cache::get($cacheKey)) {
            return $this->rehydrate($cached, true);
        }

        /** @var Response $response */
        $response = $next($request);

        // نخزّن فقط الردود JSON غير المتدفقة
        if (method_exists($response, 'getContent')) {
            $ttl = (int) config('api.idempotency.ttl_hours', 24);
            $payload = [
                'status'  => $response->getStatusCode(),
                'headers' => $this->selectHeaders($response),
                'body'    => $response->getContent(),
            ];
            Cache::put($cacheKey, $payload, now()->addHours($ttl));
        }

        $response->headers->set('Idempotency-Replayed', 'false');
        return $response;
    }

    private function isUnsafe(Request $r): bool
    {
        return in_array($r->getMethod(), ['POST','PUT','PATCH','DELETE'], true);
    }

    private function makeCacheKey(Request $r, string $idk): string
    {
        // المفتاح يعتمد على: المستخدم (إن وجد) + المسار + الطريقة + جسم الطلب + مفتاح التكرار
        $uid   = optional($r->user())->id ?? 'guest';
        $route = $r->path();
        $verb  = $r->getMethod();
        $body  = $r->getContent(); // raw body
        $hash  = hash('sha256', $verb.'|'.$route.'|'.$uid.'|'.$body.'|'.$idk);

        return 'idem:'.$hash;
    }

    private function rehydrate(array $cached, bool $replayed): Response
    {
        $response = new Response($cached['body'] ?? '', $cached['status'] ?? 200, $cached['headers'] ?? []);
        $response->headers->set('Idempotency-Replayed', $replayed ? 'true' : 'false');
        // الحفاظ على نوع المحتوى
        if (!$response->headers->has('Content-Type')) {
            $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        }
        return $response;
    }

    private function selectHeaders(Response $response): array
    {
        // نحتفظ ببعض الترويسات المفيدة فقط
        $keep = ['Content-Type','Content-Language','X-RateLimit-Limit','X-RateLimit-Remaining','Retry-After','X-Trace-Id'];
        $out  = [];
        foreach ($keep as $h) {
            if ($response->headers->has($h)) {
                $out[$h] = $response->headers->get($h);
            }
        }
        return $out;
    }
}
