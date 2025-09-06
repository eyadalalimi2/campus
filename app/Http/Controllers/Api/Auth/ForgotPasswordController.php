<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function __invoke(ForgotPasswordRequest $request)
    {
        // استجابة موحدة لمنع Email Enumeration
        Password::sendResetLink(['email' => $request->validated()['email']]);

        return response()->json([
            'status'  => 'success',
            'message' => 'إذا كان البريد مسجلاً، ستصلك رسالة لإعادة تعيين كلمة المرور.',
        ]);
    }
}
