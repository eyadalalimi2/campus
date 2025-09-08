<?php

namespace App\Http\Controllers\Api\V1\Me;

use App\Domain\Policy\ContentScopePolicy;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use App\Exceptions\Api\ApiException;

final class VisibilityController extends Controller
{
    public function __construct(private ContentScopePolicy $policy) {}

    /**
     * إظهار حالة الربط بالمؤسسة التعليمية ومصادر المحتوى المسموح بها وفق السياسة المركزية.
     */
    public function show()
    {
        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) {
            throw new ApiException('UNAUTHORIZED', 'يجب تسجيل الدخول أولاً.', 401);
        }

        $result = $this->policy->evaluate($user);

        return ApiResponse::ok($result);
    }
}
