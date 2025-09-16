<?php

namespace App\Policies;

use App\Models\StudentRequest;
use App\Models\User;

class StudentRequestPolicy
{
    public function view(User $user, StudentRequest $req): bool
    {
        return $req->user_id === $user->id;
    }

    public function update(User $user, StudentRequest $req): bool
    {
        // التعديل مسموح للمالك طالما الطلب ليس مغلقاً أو محلولاً
        return $req->user_id === $user->id
            && !in_array($req->status, ['closed','resolved']);
    }

    public function delete(User $user, StudentRequest $req): bool
    {
        // الحذف (Soft) للمالك عندما تكون الحالة مفتوحة
        return $req->user_id === $user->id && $req->status === 'open';
    }
}
