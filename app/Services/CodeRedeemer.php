<?php

namespace App\Services;

use App\Models\ActivationCode;
use Illuminate\Support\Facades\DB;

class CodeRedeemer
{
    /**
     * تفعيل كود من لوحة التحكم لطالب محدد.
     * يرجع [ok, message]
     */
    public function redeemForUser(string $code, int $userId, int $adminId): array
    {
        return DB::transaction(function () use ($code, $userId, $adminId) {
            $activationCode = ActivationCode::where('code', $code)->firstOrFail();

            if ($activationCode->is_redeemed) {
                throw new \Exception('This code has already been redeemed.');
            }

            $subscription = ActivationCode::create([
                'user_id' => $userId,
                'plan_id' => $activationCode->plan_id,
                'admin_id' => $adminId,
                'activation_code_id' => $activationCode->id,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]);

            $activationCode->update(['is_redeemed' => true]);

            return [$subscription, 'Code redeemed successfully.'];
        });
    }
    public function redeemByAdmin(string $codeStr, int $userId, ?int $adminId = null): array
    {
        return DB::transaction(function () use ($codeStr, $userId, $adminId) {

            /** @var ActivationCode|null $code */
            $code = ActivationCode::where('code',$codeStr)->lockForUpdate()->first();

            if (!$code) return [false,'الكود غير موجود.'];
            if ($code->status !== 'active') return [false,'حالة الكود غير صالحة للتفعيل.'];
            $now = now();
            if ($code->valid_from && $now->lt($code->valid_from)) return [false,'لم يبدأ صلاح الكود بعد.'];
            if ($code->valid_until && $now->gt($code->valid_until)) return [false,'انتهت صلاحية الكود.'];
            if ($code->redemptions_count >= $code->max_redemptions) return [false,'تم استهلاك الكود.'];
            if ($code->start_policy === 'fixed_start' && empty($code->starts_on)) return [false,'الكود يتطلب starts_on.'];

            // حدث السجل
            $code->redemptions_count += 1;
            $code->redeemed_by_user_id = $userId;
            $code->redeemed_at = $now;
            if ($code->redemptions_count >= $code->max_redemptions) {
                $code->status = 'redeemed';
            }
            $code->save();

            // (اختياري) إنشاء اشتراك هنا حسب منطقك إن لزم

            return [true, 'تم تفعيل الكود بنجاح.'];
        });
    }
}
