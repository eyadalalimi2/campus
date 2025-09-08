<?php

namespace App\Http\Controllers\Api\V1\Me;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Me\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Support\ApiResponse;
use App\Exceptions\Api\ApiException;
use App\Models\User;

final class SecurityController extends Controller
{
    /**
     * تغيير كلمة المرور للمستخدم الحالي
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) {
            throw new ApiException('UNAUTHORIZED', 'يجب تسجيل الدخول أولاً.', 401);
        }

        // البيانات المتحقَّقة من الـ Request
        $data = $request->validated();
        $current = $data['current_password'];
        $new     = $data['new_password'];

        // تحقق من كلمة المرور الحالية
        if (!Hash::check($current, $user->password)) {
            return ApiResponse::error(
                'INVALID_CURRENT_PASSWORD',
                'كلمة المرور الحالية غير صحيحة.',
                ['current_password' => ['كلمة المرور الحالية غير صحيحة']],
                422
            );
        }

        // تحديث كلمة المرور + إبطال رموز التذكر
        $user->password = Hash::make($new);
        $user->setRememberToken(Str::random(60));
        $user->save();

        // تسجيل خروج جميع الجلسات الأخرى (إبقاء الجلسة الحالية فقط)
        $currentTokenId = $user->currentAccessToken()?->id;
        if ($currentTokenId) {
            $user->tokens()->where('id', '!=', $currentTokenId)->delete();
        }

        return ApiResponse::ok([
            'message' => 'تم تحديث كلمة المرور بنجاح. تم تسجيل الخروج من جميع الجلسات الأخرى.'
        ]);
    }
}
