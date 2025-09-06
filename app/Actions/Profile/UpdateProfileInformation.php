<?php

namespace App\Actions\Profile;

use App\Models\User;

class UpdateProfileInformation
{
    public function execute(User $user, array $data): User
    {
        $user->fill($data);

        // إذا تم تغيير البريد، ألغِ التحقق
        if (array_key_exists('email', $data) && $data['email'] !== $user->getOriginal('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // في حال تغيير البريد، أرسل رسالة التفعيل من جديد
        if (array_key_exists('email', $data)) {
            $user->sendEmailVerificationNotification();
        }

        return $user->fresh();
    }
}
