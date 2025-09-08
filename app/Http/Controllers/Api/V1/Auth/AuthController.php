<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Exceptions\Api\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Requests\Api\V1\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\V1\Auth\ResetPasswordRequest;
use App\Http\Requests\Api\V1\Auth\VerifyEmailRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use App\Support\ApiResponse;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

final class AuthController extends Controller
{
    /**
     * تسجيل مستخدم جديد + إنشاء توكن Sanctum مرتبط بـ login_device
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        // إنشاء المستخدم
        $user = User::create([
            'student_number' => $data['student_number'] ?? null,
            'name'           => $data['name'],
            'email'          => $data['email'],
            'phone'          => $data['phone'] ?? null,
            'country_id'     => $data['country_id'],
            'university_id'  => $data['university_id'] ?? null,
            'college_id'     => $data['college_id'] ?? null,
            'major_id'       => $data['major_id'] ?? null,
            'level'          => $data['level'] ?? null,
            'gender'         => $data['gender'] ?? null,
            'status'         => User::STATUS_ACTIVE,
            'password'       => $data['password'],
        ]);

        // إنشاء توكن مع القدرات المطلوبة لمسارات المجموعة
        $token = $user->createToken(
            $data['login_device'],
            ['me:read', 'structure:read', 'catalog:read', 'subscription:write']
        )->plainTextToken;

        // إرسال/توليد كود تحقق البريد (اختياري عند التسجيل)
        $this->issueEmailVerificationCode($user);

        return ApiResponse::ok([
            'token' => $token,
            'user'  => new UserResource($user),
        ]);
    }

    /**
     * تسجيل الدخول + إنشاء توكن Sanctum مرتبط بـ login_device
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        /** @var User|null $user */
        $user = User::query()->where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new ApiException('AUTH_INVALID', 'بيانات الاعتماد غير صحيحة.', 401);
        }

        if ($user->status === User::STATUS_SUSPENDED) {
            throw new ApiException('ACCOUNT_SUSPENDED', 'الحساب موقوف مؤقتًا.', 403);
        }

        // إنشاء التوكن
        $token = $user->createToken(
            $data['login_device'],
            ['me:read', 'structure:read', 'catalog:read', 'subscription:write']
        )->plainTextToken;

        return ApiResponse::ok([
            'token' => $token,
            'user'  => new UserResource($user),
        ]);
    }

    /**
     * إعادة إرسال كود تحقق البريد (OTP) إلى البريد
     * ملاحظة: لا نكشف وجود البريد. نعيد 200 دائمًا.
     */
    public function resendEmailVerification(Request $request)
    {
        $email = $this->normalizeEmail($request->string('email')->toString());

        if ($email) {
            $user = User::query()->where('email', $email)->first();
            if ($user && is_null($user->email_verified_at)) {
                $this->issueEmailVerificationCode($user);
                // هنا ضع إرسال بريد فعلي إن توفر مزود البريد
            }
        }

        return ApiResponse::ok(['message' => 'إن وُجد الحساب سيتم إرسال رمز تحقق للبريد.']);
    }

    /**
     * تحقق البريد عبر كود OTP رقمي (4-8)
     */
    public function verifyEmail(VerifyEmailRequest $request)
    {
        $data = $request->validated();

        /** @var User|null $user */
        $user = User::query()->where('email', $data['email'])->first();
        if (!$user) {
            // لا نكشف وجود/عدم وجود البريد
            return ApiResponse::ok(['message' => 'تم التحقق (إن كان الرمز صالحًا).']);
        }

        if ($user->email_verified_at) {
            return ApiResponse::ok(['message' => 'البريد مُتحقق مسبقًا.']);
        }

        $cacheKey = $this->emailVerificationCacheKey($user->email);
        $expected = Cache::get($cacheKey);

        if (!$expected || $expected !== $data['code']) {
            throw new ApiException('OTP_INVALID', 'رمز التحقق غير صحيح أو منتهي.', 422);
        }

        $user->forceFill(['email_verified_at' => now()])->save();
        Cache::forget($cacheKey);

        return ApiResponse::ok(['message' => 'تم التحقق من البريد بنجاح.']);
    }

    /**
     * بداية تدفق نسيان كلمة المرور — يعيد 200 دائمًا
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $email = $request->validated()['email'];

        // نستخدم Laravel Password Broker (سيرسل البريد إن كانت الإعدادات مهيأة)
        // نعيد 200 بغض النظر لتفادي كشف وجود البريد.
        Password::broker()->sendResetLink(['email' => $email]);

        return ApiResponse::ok(['message' => 'إن وُجد الحساب سيتم إرسال رابط/رمز الاستعادة.']);
    }

    /**
     * إعادة تعيين كلمة المرور باستخدام التوكن المخزن بجدول password_reset_tokens
     */
    public function resetPassword(ResetPasswordRequest $request)
    {
        $data = $request->validated();

        $status = Password::reset(
            [
                'email'                 => $data['email'],
                'token'                 => $data['token'],
                'password'              => $data['password'],
                'password_confirmation' => $data['password_confirmation'],
            ],
            function (User $user, $password) {
                $user->forceFill([
                    'password'       => $password,
                    'remember_token' => null,
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            // لا نُفصح بالتفاصيل (رمز غير صحيح/منتهي)
            throw new ApiException('RESET_FAILED', 'فشل إعادة التعيين. الرمز غير صحيح أو منتهي.', 422);
        }

        return ApiResponse::ok(['message' => 'تم تعيين كلمة المرور بنجاح. يمكنك تسجيل الدخول الآن.']);
    }

    /**
     * بياناتي
     */
    public function me(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        return ApiResponse::ok(new UserResource($user));
    }

    /**
     * تسجيل الخروج: حذف التوكن الحالي فقط
     */
    public function logout(Request $request)
    {
        $token = $request->user()?->currentAccessToken();
        if ($token) {
            /** @var \Laravel\Sanctum\PersonalAccessToken $token */
            $token->delete();
        }

        return ApiResponse::ok(['message' => 'تم تسجيل الخروج بنجاح.']);
    }

    /* =========================
     * Helpers
     * ========================= */

    private function normalizeEmail(?string $email): ?string
    {
        if ($email === null) return null;
        $email = trim($email);
        return $email === '' ? null : mb_strtolower($email);
    }

    /**
     * توليد وحفظ كود تحقق البريد (OTP) في الـ Cache لمدة 15 دقيقة
     */
    private function issueEmailVerificationCode(User $user): void
    {
        // توليد OTP رقمي 6 خانات
        $code = $this->numericOtp(6);

        $ttlMinutes = 15;
        Cache::put($this->emailVerificationCacheKey($user->email), $code, now()->addMinutes($ttlMinutes));

        // مكان إرسال البريد الفعلي (Mail::to($user->email)->send(...))
        // لأغراض الإنتاج، احرص على عدم إرجاع الكود في الاستجابة.
    }

    private function emailVerificationCacheKey(string $email): string
    {
        return 'verify_email:' . sha1(mb_strtolower($email));
    }

    private function numericOtp(int $length = 6): string
    {
        $length = max(4, min($length, 8));
        $out = '';
        for ($i = 0; $i < $length; $i++) {
            $out .= random_int(0, 9);
        }
        return $out;
    }
}
