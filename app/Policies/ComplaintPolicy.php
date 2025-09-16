<?php

namespace App\Policies;

use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    public function view(User $user, Complaint $complaint): bool
    {
        return $complaint->user_id === $user->id;
    }

    public function update(User $user, Complaint $complaint): bool
    {
        // يسمح للمالك بالتعديل ما لم تُغلق نهائياً
        return $complaint->user_id === $user->id
            && !in_array($complaint->status, ['closed']);
    }

    public function delete(User $user, Complaint $complaint): bool
    {
        // حذف (Soft) فقط إذا كانت مفتوحة ولم يبدأ التعامل معها
        return $complaint->user_id === $user->id
            && in_array($complaint->status, ['open']);
    }
}
