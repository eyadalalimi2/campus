<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('admins', 'email')->ignore($admin->id)
            ],
            'current_password' => ['nullable', 'required_with:new_password', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // Update basic info
        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        // Handle password change if requested
        if (!empty($validated['new_password'])) {
            if (empty($validated['current_password']) || !Hash::check($validated['current_password'], $admin->password)) {
                return back()
                    ->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة'])
                    ->withInput($request->except(['current_password', 'new_password', 'new_password_confirmation']));
            }

            $admin->password = Hash::make($validated['new_password']);
        }

        $admin->save();

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }
}
