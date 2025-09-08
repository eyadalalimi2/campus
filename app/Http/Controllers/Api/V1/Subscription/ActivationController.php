<?php

namespace App\Http\Controllers\Api\V1\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Subscription\ActivateCodeRequest;
use App\Actions\Subscription\ActivateCodeAction;
use App\Exceptions\Api\ApiException;
use App\Exceptions\Api\Handler as ApiHandler;
use App\Support\ApiResponse;
use App\Http\Resources\Api\V1\SubscriptionResource;

final class ActivationController extends Controller
{
    public function __construct(private ActivateCodeAction $action) {}

    /**
     * تفعيل كود الاشتراك للمستخدم المصادق عليه
     * يتطلّب: auth:sanctum + ability "subscription:write"
     */
    public function redeem(ActivateCodeRequest $request)
    {
        try {
            // قراءة بيانات الطلب بشكل آمن
            $data   = $request->validated();
            $code   = $data['code'];

            // جلب معرّف المستخدم المصادق عليه بشكل صريح (تجنّب تحذير IDE من user())
            $userId = auth()->id();
            if (!$userId) {
                throw new ApiException('UNAUTHORIZED', 'يجب تسجيل الدخول لتنفيذ العملية.', 401);
            }

            // تنفيذ عملية التفعيل
            $subscription = $this->action->handle($userId, $code);

            // نفترض أن الاكشن يعيد نموذج Eloquent للاشتراك
            return ApiResponse::ok(new SubscriptionResource($subscription));
        } catch (ApiException $e) {
            return ApiHandler::render($e);
        }
    }
}
