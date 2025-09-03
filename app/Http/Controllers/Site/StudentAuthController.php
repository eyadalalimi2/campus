<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class StudentAuthController extends Controller
{
    public function showLogin()    { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }
    public function showForgot()   { return view('auth.forgot'); }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email','password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // لو بريد غير مفعّل، ودّيه لصفحة التنبيه
            if (! $request->user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')->with('status','الرجاء تفعيل بريدك الإلكتروني.');
            }

            return redirect()->route('student.dashboard');
        }

        return back()->withErrors(['email'=>'بيانات الدخول غير صحيحة.'])->withInput();
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        // تسجيل دخوله مباشرة (اختياري)
        Auth::login($user);
        event(new Registered($user)); // هذا يفعّل إرسال بريد التحقق

        return redirect()->route('verification.notice')->with('status','تم إنشاء الحساب. الرجاء تفعيل بريدك الإلكتروني.');
    }

    public function sendResetLink(ForgotPasswordRequest $request)
    {
        $status = PasswordBroker::sendResetLink($request->only('email'));

        return $status === PasswordBroker::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email'=> __($status)]);
    }

    public function showReset(Request $r, string $token)
    {
        return view('auth.reset', ['token'=>$token, 'email'=>$r->query('email')]);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $status = PasswordBroker::reset(
            $request->only('email','password','password_confirmation','token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === PasswordBroker::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email'=> __($status)])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
