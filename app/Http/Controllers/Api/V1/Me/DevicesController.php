<?php

namespace App\Http\Controllers\Api\V1\Me;

use App\Exceptions\Api\ApiException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

final class DevicesController extends Controller
{
    /**
     * قائمة أجهزة/جلسات المستخدم (Tokens)
     */
    public function index(Request $request)
    {
        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) {
            throw new ApiException('UNAUTHORIZED', 'يجب تسجيل الدخول لعرض الأجهزة.', 401);
        }

        $currentId = $user->currentAccessToken()?->id;

        // نجلب آخر 100 توكن مرتبة حسب آخر استخدام ثم تاريخ الإنشاء
        $tokens = PersonalAccessToken::query()
            ->select(['id', 'name', 'abilities', 'last_used_at', 'created_at', 'expires_at'])
            ->where('tokenable_type', User::class)
            ->where('tokenable_id', $user->id)
            ->orderByDesc('last_used_at')
            ->orderByDesc('created_at')
            ->limit(100)
            ->get()
            ->map(function (PersonalAccessToken $t) use ($currentId) {
                return [
                    'id'           => $t->id,
                    'name'         => $t->name,
                    'abilities'    => is_array($t->abilities) ? $t->abilities : (json_decode((string) $t->abilities, true) ?: []),
                    'last_used_at' => $t->last_used_at,
                    'created_at'   => $t->created_at,
                    'expires_at'   => $t->expires_at,
                    'is_current'   => $t->id === $currentId,
                ];
            });

        return ApiResponse::ok($tokens);
    }

    /**
     * حذف جهاز/جلسة محددة (Token) مملوكة للمستخدم
     */
    public function destroy(Request $request, $tokenId)
    {
        /** @var User|null $user */
        $user = auth()->user();
        if (!$user) {
            throw new ApiException('UNAUTHORIZED', 'يجب تسجيل الدخول لحذف الأجهزة.', 401);
        }

        // التحقق من أن المعرف رقم صحيح
        if (!ctype_digit((string) $tokenId)) {
            return ApiResponse::error('NOT_FOUND', 'الجهاز غير موجود.', [], 404);
        }
        $tokenId = (int) $tokenId;

        /** @var PersonalAccessToken|null $token */
        $token = PersonalAccessToken::query()
            ->where('id', $tokenId)
            ->where('tokenable_type', User::class)
            ->where('tokenable_id', $user->id)
            ->first();

        if (!$token) {
            return ApiResponse::error('NOT_FOUND', 'الجهاز غير موجود.', [], 404);
        }

        $token->delete();

        return ApiResponse::ok(['message' => 'تم حذف الجهاز/الجلسة.']);
    }
}
