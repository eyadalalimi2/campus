<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\IssuePersonalAccessToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request, IssuePersonalAccessToken $issueToken)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'بيانات الاعتماد غير صحيحة.',
            ], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (is_null($user->email_verified_at)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'الرجاء تفعيل البريد الإلكتروني قبل تسجيل الدخول.',
            ], 403);
        }

        $deviceName = $request->input('device_name', 'Android');
        $token = $issueToken->execute($user, $deviceName);

        return response()->json([
            'status'  => 'success',
            'message' => 'تم تسجيل الدخول.',
            'data'    => [
                'user'  => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);
    }
}
