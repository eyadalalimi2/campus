<?php

namespace App\Services;

use App\Models\Notification;

class Notify
{
    /**
     * إنشاء إشعار مبسّط لمستخدم واحد.
     * يعيد كائن الإشعار أو null عند الفشل (ولا يرمي استثناء).
     */
    public static function toUser(
        int $userId,
        string $title,
        ?string $body = null,
        ?string $type = null,
        ?string $targetType = null,
        ?int $targetId = null,
        array $data = []
    ): ?Notification {
        try {
            return Notification::create([
                'user_id'     => $userId,
                'title'       => $title,
                'body'        => $body,
                'type'        => $type,
                'target_type' => $targetType,
                'target_id'   => $targetId,
                'data'        => $data,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        } catch (\Throwable $e) {
            // لا تُعطّل مسار العمل بسبب الإشعارات
            return null;
        }
    }
}
