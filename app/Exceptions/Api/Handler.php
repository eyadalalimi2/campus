<?php
namespace App\Exceptions\Api;

use App\Support\ApiResponse;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class Handler
{
    public static function render(Throwable $e)
    {
        // إذا كان ApiException مخصص → استخدمه
        if ($e instanceof ApiException) {
            return ApiResponse::error($e->apiCode, $e->getMessage(), $e->fields, $e->status);
        }

        // معالجة أخطاء التحقق من الفاليديشن
        if ($e instanceof ValidationException) {
            return ApiResponse::error(
                'VALIDATION_ERROR',
                'فشل التحقق من البيانات',
                $e->errors(),
                422
            );
        }

        // في حالة عدم العثور على موديل
        if ($e instanceof ModelNotFoundException) {
            return ApiResponse::error('NOT_FOUND', 'المورد غير موجود', [], 404);
        }

        // معالجة استثناءات HTTP العامة
        if ($e instanceof HttpException) {
            return ApiResponse::error('HTTP_ERROR', $e->getMessage(), [], $e->getStatusCode());
        }

        // fallback لأي خطأ غير متوقع
        return ApiResponse::error('SERVER_ERROR', 'حدث خطأ غير متوقع', [], 500);
    }
}
