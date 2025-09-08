<?php

namespace App\Http\Controllers\Api\V1\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\SubscriptionResource;
use App\Models\Subscription;
use App\Support\ApiResponse;
use App\Exceptions\Api\ApiException;

final class SubscriptionsController extends Controller
{
    /**
     * الاشتراك النشط الحالي للمستخدم.
     */
    public function active()
    {
        $user = auth()->user();
        if (!$user) {
            throw new ApiException('UNAUTHORIZED', 'يجب تسجيل الدخول للوصول إلى الاشتراك.', 401);
        }

        // يفترض وجود scopeActive() على موديل Subscription
        $sub = $user->subscriptions()
            ->active()
            ->orderByDesc('ends_at')
            ->with(['plan']) // إن كان لديك علاقة plan() على Subscription
            ->first();

        return ApiResponse::ok($sub ? new SubscriptionResource($sub) : null);
    }

    /**
     * كل اشتراكات المستخدم (أحدث أولاً) – حد 100.
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            throw new ApiException('UNAUTHORIZED', 'يجب تسجيل الدخول للوصول إلى قائمة الاشتراكات.', 401);
        }

        $subs = $user->subscriptions()
            ->with(['plan']) // اختياري
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        return ApiResponse::ok(SubscriptionResource::collection($subs));
    }
}
