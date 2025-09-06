<?php

namespace App\Actions\Auth;

use App\Models\User;

class IssuePersonalAccessToken
{
    public function execute(User $user, string $deviceName = 'Android'): string
    {
        // يمكن إضافة Scopes أو Expiration مخصّص هنا مستقبلاً
        return $user->createToken($deviceName)->plainTextToken;
    }
}
