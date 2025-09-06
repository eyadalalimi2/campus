<?php

namespace App\Actions\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class UpdateUserPassword
{
    public function execute(User $user, array $data): void
    {
        if (!Hash::check($data['current_password'], $user->password)) {
            throw new InvalidArgumentException('كلمة المرور الحالية غير صحيحة.');
        }

        $user->forceFill([
            'password' => Hash::make($data['password']),
        ])->save();

        // إلغاء التوكن الحالي لإجبار إعادة تسجيل الدخول من الأجهزة
        if (method_exists($user, 'tokens')) {
            $user->tokens()->delete();
        }
    }
}
