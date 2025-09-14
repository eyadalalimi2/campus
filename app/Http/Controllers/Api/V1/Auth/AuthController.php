<?php

namespace App\Http\Controllers\Api\V1\Auth;

use Illuminate\Support\Facades\Storage;
use App\Exceptions\Api\ApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Requests\Api\V1\Auth\ForgotPasswordRequest;
use App\Http\Requests\Api\V1\Auth\ResetPasswordRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Mail\EmailVerificationLink;
use App\Models\User;
use App\Support\ApiResponse;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

final class AuthController extends Controller
{
    /**
     * تسجيل مستخدم جديد + إنشاء توكن Sanctum مرتبط بـ login_device
     * ثم إرسال رابط تفعيل البريد.
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

        // إنشاء توكن الوصول
        $token = $user->createToken(
            $data['login_device'],
            ['me:read', 'structure:read', 'catalog:read', 'subscription:write']
        )->plainTextToken;

        // إرسال رابط تفعيل البريد
        $this->issueAndSendEmailVerificationLink($user->email, $user->name ?? 'طالبنا العزيز');

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
     * إعادة إرسال "رابط" تفعيل البريد.
     * لا نكشف وجود/عدم وجود البريد؛ نعيد 200 دائمًا.
     */
    public function resendEmailVerificationLink(Request $request)
    {
        $email = $this->normalizeEmail($request->input('email'));
        if ($email) {
            $user = DB::table('users')->where('email', $email)->first();
            if ($user && empty($user->email_verified_at)) {
                $this->issueAndSendEmailVerificationLink($email, $user->name ?? 'طالبنا العزيز');
            }
        }

        return ApiResponse::ok(['message' => 'إن وُجد الحساب سيتم إرسال رابط التفعيل إلى البريد.']);
    }

    /**
     * تفعيل البريد عبر "التوكن" الموجود في الرابط.
     * GET /api/v1/auth/email/verify/{token}
     */
    public function verifyEmailByToken(Request $request, string $token)
    {
        $row = DB::table('email_verification_tokens')->where('token', $token)->first();
        if (!$row) {
            return ApiResponse::error('TOKEN_INVALID', 'رابط التفعيل غير صالح.', [], 422);
        }

        if (!empty($row->used_at)) {
            return ApiResponse::error('TOKEN_USED', 'تم استخدام رابط التفعيل مسبقًا.', [], 422);
        }

        if (Carbon::parse($row->expires_at)->isPast()) {
            return ApiResponse::error('TOKEN_EXPIRED', 'انتهت صلاحية رابط التفعيل.', [], 422);
        }

        // تفعيل البريد
        DB::table('users')->where('email', $row->email)->update(['email_verified_at' => Carbon::now()]);
        // وسم هذا التوكن كمستخدم
        DB::table('email_verification_tokens')->where('id', $row->id)->update(['used_at' => Carbon::now()]);
        // تنظيف أي توكنات متبقية لنفس البريد
        DB::table('email_verification_tokens')
            ->where('email', $row->email)
            ->whereNull('used_at')
            ->delete();

        // يمكن بدلاً من JSON أن نعيد توجيه Deep Link للتطبيق:
        // return redirect()->away('com.eyadalalimi.students://email-verified');
        return redirect()->away('com.eyadalalimi.students://email-verified');
    }

    /**
     * بداية تدفّق "نسيت كلمة المرور" — يعيد 200 دائمًا
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $email = $request->validated()['email'];

        // استخدام Laravel Password Broker لإرسال رابط/رمز الاستعادة
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
    /**
     * حذف الحساب نهائيًا بعد التحقق من كلمة المرور الحالية.
     * DELETE /api/v1/me/account
     * Body: { "current_password": "..." }
     */
    public function destroyAccount(Request $request)
    {
        // تحقّق من وجود كلمة المرور
        $data = $request->validate([
            'current_password' => ['required', 'string', 'min:6'],
        ]);

        /** @var User $user */
        $user = $request->user();

        // مطابقة كلمة المرور
        if (! Hash::check($data['current_password'], $user->password)) {
            throw new \App\Exceptions\Api\ApiException('INVALID_PASSWORD', 'كلمة المرور غير صحيحة.', 422);
        }

        DB::transaction(function () use ($user) {
            // (اختياري) حذف صورة البروفايل من التخزين إن وُجدت
            if (!empty($user->profile_photo_path)) {
                // لو كنت تخزن بـ storage/app/public واستخدمت storage/ للعرض
                $path = ltrim(preg_replace('#^storage/#', '', $user->profile_photo_path), '/');
                try {
                    Storage::disk('public')->delete($path);
                } catch (\Throwable $e) {
                }
            }

            // حذف جميع توكنات Sanctum (الجلسات)
            try {
                $user->tokens()->delete();
            } catch (\Throwable $e) {
            }

            // TODO: لو عندك علاقات بقيود FK بلا cascade، احذف/افرغ هنا حسب الحاجة

            // حذف الحساب (حسب إعدادك: نهائي أو SoftDelete إن مفعل)
            $user->delete();
        });

        return \App\Support\ApiResponse::ok(['message' => 'تم حذف الحساب']);
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
     * إنشاء توكن تحقق بريد وحفظه وإرسال بريد يحتوي رابط التفعيل.
     * - صلاحية 10 دقائق
     * - حد إرسال 5 مرات لكل بريد خلال 24 ساعة (حماية من الإساءة)
     */
    private function issueAndSendEmailVerificationLink(string $email, string $userName = 'طالبنا العزيز'): void
    {
        // حد الإرسال اليومي
        $sendCount = DB::table('email_verification_tokens')
            ->where('email', $email)
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();

        if ($sendCount >= 5) {
            // لا نرمي خطأ للمستخدم علنًا؛ فقط نتوقف عن الإرسال
            return;
        }

        // إنشاء التوكن
        $token = Str::random(64);

        DB::table('email_verification_tokens')->insert([
            'email'      => $email,
            'token'      => $token,
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // بناء الرابط (API GET)
        $verifyUrl = url('/api/v1/auth/email/verify/' . $token);

        // إرسال البريد
        try {
            Mail::to($email)->send(new EmailVerificationLink($verifyUrl, $userName));
        } catch (\Throwable $e) {
            // لا نفصح عن الفشل للمستخدم (لأسباب أمان/خصوصية)
            // يمكن تسجيل الخطأ في اللوج فقط
            // \Log::error('Mail send failed: '.$e->getMessage());
        }
    }
}
