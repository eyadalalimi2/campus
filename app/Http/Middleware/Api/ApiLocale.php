<?php
namespace App\Http\Middleware\Api;

use Closure;

class ApiLocale
{
    public function handle($request, Closure $next)
    {
        app()->setLocale('ar');
        return $next($request);
    }
}
