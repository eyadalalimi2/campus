<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;

class Handler extends ExceptionHandler
{
    protected $levels = [
        // 
    ];

    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        // تجربة محسّنة لمسار تفعيل البريد: إن كان الرابط غير صالح/منتهي أو حدثت حالة خاصة، أظهر صفحة ودية
        try {
            if (method_exists($request, 'route') && $request->route() && $request->routeIs('verification.verify')) {
                // رابط موقّع غير صالح (Expired/تم التلاعب به)
                if ($e instanceof \Illuminate\Routing\Exceptions\InvalidSignatureException) {
                    return response()->view('auth.verify-link-invalid', [
                        'reason' => 'invalid',
                    ], 403);
                }
                // غير مصدّق: أعِد التوجيه لتسجيل الدخول برسالة لطيفة بدل JSON
                if ($e instanceof AuthenticationException) {
                    return redirect()->guest(route('login'))
                        ->with('status', 'يرجى تسجيل الدخول لإكمال تفعيل بريدك.');
                }
            }
        } catch (\Throwable $ignore) {}

        // نرجع JSON لطلبات API أو لمن يطلب صراحة JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            // Validation
            if ($e instanceof ValidationException) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'فشل التحقق من البيانات.',
                    'errors'  => $e->errors(),
                ], 422);
            }

            // Unauthenticated
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'غير مصدّق. يرجى تسجيل الدخول.',
                ], 401);
            }

            // Forbidden
            if ($e instanceof AuthorizationException) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'غير مصرح لك بتنفيذ هذا الإجراء.',
                ], 403);
            }

            // 404
            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'المورد غير موجود.',
                ], 404);
            }

            // 405
            if ($e instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'طريقة HTTP غير مدعومة لهذا المسار.',
                ], 405);
            }

            // 429
            if ($e instanceof ThrottleRequestsException) {
                $retryAfter = $e->getHeaders()['Retry-After'] ?? null;

                return response()->json([
                    'status'  => 'error',
                    'message' => 'محاولات كثيرة. حاول لاحقًا.',
                ], 429, $retryAfter ? ['Retry-After' => $retryAfter] : []);
            }

            // Http Exceptions عامة
            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                $message = $status >= 500
                    ? 'حدث خطأ غير متوقع.'
                    : ($e->getMessage() ?: 'حدث خطأ أثناء معالجة الطلب.');

                return response()->json([
                    'status'  => 'error',
                    'message' => app()->isProduction() ? ($status >= 500 ? 'حدث خطأ غير متوقع.' : $message) : $message,
                ], $status);
            }

            // باقي الأخطاء = 500
            return response()->json([
                'status'  => 'error',
                'message' => app()->isProduction() ? 'حدث خطأ غير متوقع.' : ($e->getMessage() ?: 'حدث خطأ غير متوقع.'),
            ], 500);
        }

        // سلوك الويب الطبيعي
        return parent::render($request, $e);
    }
}
