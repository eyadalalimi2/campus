<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

final class ApiLocale
{
    /** اللغات المسموح بها */
    private array $supported = ['ar', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        // أولوية: X-Locale ثم Accept-Language ثم الافتراضي (ar)
        $locale = $request->headers->get('X-Locale')
            ?: Str::of((string)$request->headers->get('Accept-Language'))
                ->before(',')->before(';')->trim()->value();

        $locale = $this->normalize($locale);

        app()->setLocale($locale);
        try { Carbon::setLocale($locale); } catch (\Throwable) {}

        /** @var Response $response */
        $response = $next($request);
        $response->headers->set('Content-Language', $locale);

        return $response;
    }

    private function normalize(?string $locale): string
    {
        if (!$locale) return 'ar';
        $short = Str::of($locale)->replace('_', '-')->lower()->before('-')->value();
        return in_array($short, $this->supported, true) ? $short : 'ar';
    }
}
