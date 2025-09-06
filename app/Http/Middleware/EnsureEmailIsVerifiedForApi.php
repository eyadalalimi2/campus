<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEmailIsVerifiedForApi
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user && is_null($user->email_verified_at)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'حساب غير مُفعل بالبريد الإلكتروني.',
            ], 403);
        }

        return $next($request);
    }
}
