<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'البريد مُفعل مسبقًا.',
            ]);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'status'  => 'success',
            'message' => 'تم إرسال رابط التحقق إلى بريدك.',
        ]);
    }
}
