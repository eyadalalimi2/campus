<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin() { return view('admin.auth.login'); }

    public function login(Request $r) {
        $r->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'remember' => 'nullable|boolean'
        ]);

        if (Auth::guard('admin')->attempt($r->only('email','password'), (bool)$r->remember)) {
            $r->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        return back()->withErrors(['email'=>'بيانات الدخول غير صحيحة'])->onlyInput('email');
    }

    public function logout(Request $r) {
        Auth::guard('admin')->logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
