<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class StudentVerificationController extends Controller
{
    public function notice(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('student.dashboard');
        }
        return view('auth.verify');
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('student.dashboard')->with('status','تم التفعيل مسبقًا.');
        }

        $request->fulfill();
        return redirect()->route('student.dashboard')->with('status','تم تفعيل بريدك بنجاح.');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('student.dashboard');
        }
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status','تم إرسال رابط تفعيل جديد.');
    }
}
