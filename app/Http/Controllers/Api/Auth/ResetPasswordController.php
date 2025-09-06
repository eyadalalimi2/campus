<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function __invoke(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email','password','password_confirmation','token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                // إلغاء كل التوكنات القديمة
                if (method_exists($user, 'tokens')) {
                    $user->tokens()->delete();
                }

                event(new PasswordReset($user));
            }
        );

        return response()->json([
            'status'  => 'success',
            'message' => ($status === Password::PASSWORD_RESET)
                ? 'تمت إعادة تعيين كلمة المرور.'
                : 'تعذر إتمام العملية.',
        ], $status === Password::PASSWORD_RESET ? 200 : 422);
    }
}
