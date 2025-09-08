<?php
namespace App\Http\Middleware\Api;

use Closure;

class ForceJson
{
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
