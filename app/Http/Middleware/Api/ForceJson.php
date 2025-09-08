<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class ForceJson
{
    public function handle(Request $request, Closure $next): Response
    {
        // فرض قبول JSON مهما أرسل العميل
        $request->headers->set('Accept', 'application/json');

        /** @var Response $response */
        $response = $next($request);

        // توحيد ترويسة نوع المحتوى
        if (!$response->headers->has('Content-Type')) {
            $response->headers->set('Content-Type', 'application/json; charset=utf-8');
        }

        // تمكين التعرف على التتبع إن وُجد
        if ($traceId = $request->headers->get('X-Trace-Id')) {
            $response->headers->set('X-Trace-Id', $traceId);
        }

        return $response;
    }
}
