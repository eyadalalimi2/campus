<?php

namespace App\Services;

use App\Models\ActivationCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CodeRedeemer
{
    /**
     * تنفيذ عملية الاسترداد لمستخدم محدد.
     * يُعيد سجل الكود بعد التحديث.
     */
    public function redeem(string $codeValue, int $userId): ActivationCode
    {
        return DB::transaction(function () use ($codeValue, $userId) {
            /** @var ActivationCode $code */
            $code = ActivationCode::where('code', $codeValue)->lockForUpdate()->first();

            if (!$code) {
                throw new \InvalidArgumentException('الكود غير موجود.');
            }

            if (!$code->canRedeem()) {
                throw new \RuntimeException('لا يمكن استرداد هذا الكود (غير نشط/منتهي/مستنفد).');
            }

            $now = now();

            // حساب فترة الصلاحية الفعلية إذا لزم
            if ($code->start_policy === 'on_redeem') {
                if (!$code->valid_from) {
                    $code->valid_from = $now;
                }
                if (!$code->valid_until) {
                    $code->valid_until = (clone $code->valid_from)->addDays($code->duration_days);
                }
            } else { // fixed_start
                if ($code->starts_on && !$code->valid_from) {
                    $code->valid_from = Carbon::parse($code->starts_on)->startOfDay();
                }
                if (!$code->valid_until && $code->valid_from) {
                    $code->valid_until = (clone $code->valid_from)->addDays($code->duration_days);
                }
            }

            // تحقق النوافذ
            if ($code->valid_from && $now->lt($code->valid_from)) {
                throw new \RuntimeException('الاسترداد غير متاح بعد (قبل نافذة الصلاحية).');
            }
            if ($code->valid_until && $now->gt($code->valid_until)) {
                $code->status = 'expired';
                $code->save();
                throw new \RuntimeException('انتهت صلاحية الكود.');
            }

            // التحديثات
            $code->redemptions_count = min($code->max_redemptions, $code->redemptions_count + 1);
            $code->redeemed_by_user_id = $userId;
            $code->redeemed_at = $now;

            if ($code->redemptions_count >= $code->max_redemptions) {
                $code->status = 'redeemed';
            }

            $code->save();

            return $code->refresh();
        });
    }
}
