<?php

namespace App\Services;

use App\Models\ActivationCodeBatch;
use App\Models\ActivationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class ActivationCodeGenerator
{
    /**
     * واجهة ثابتة للتوافق مع أي استدعاءات سابقة
     */
    public static function generateUniqueSet(ActivationCodeBatch $batch, ?int $createdByAdminId = null): array
    {
        return (new self())->generateForBatch($batch, $createdByAdminId);
    }

    /**
     * توليد الأكواد لدُفعة معينة وإنشاء السجلات.
     * يعيد مصفوفة الأكواد التي تم إنشاؤها.
     */
    public function generateForBatch(ActivationCodeBatch $batch, ?int $createdByAdminId = null): array
    {
        $createdByAdminId = $createdByAdminId ?? (Auth::guard('admin')->id() ?? $batch->created_by_admin_id);

        $alphabet = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ'; // بدون 0,1,I,O لتقليل الالتباس
        $need = max(1, (int)$batch->quantity);
        $totalLen = max(6, (int)$batch->code_length);
        $prefix = (string)($batch->code_prefix ?? '');
        $randLen = max(4, $totalLen - mb_strlen($prefix));
        $now = now();

        $generated = [];

        DB::transaction(function () use ($batch, $createdByAdminId, $alphabet, $need, $prefix, $randLen, $now, &$generated) {
            for ($i = 0; $i < $need; $i++) {
                // حاول حتى تحصل على كود فريد
                $code = null;
                for ($attempt = 0; $attempt < 10; $attempt++) {
                    $random = self::randomFromAlphabet($alphabet, $randLen);
                    $candidate = $prefix . $random;

                    if (!ActivationCode::where('code', $candidate)->lockForUpdate()->exists()) {
                        $code = $candidate;
                        break;
                    }
                }

                if (!$code) {
                    throw new \RuntimeException('تعذّر توليد كود فريد بعد عدة محاولات.');
                }

                $rec = ActivationCode::create([
                    'batch_id'           => $batch->id,
                    'code'               => $code,
                    'plan_id'            => $batch->plan_id,
                    'university_id'      => $batch->university_id,
                    'college_id'         => $batch->college_id,
                    'major_id'           => $batch->major_id,
                    'duration_days'      => $batch->duration_days,
                    'start_policy'       => $batch->start_policy,
                    'starts_on'          => $batch->starts_on,
                    'valid_from'         => $batch->valid_from,
                    'valid_until'        => $batch->valid_until,
                    'max_redemptions'    => 1,
                    'redemptions_count'  => 0,
                    'status'             => 'active',
                    'created_by_admin_id'=> $createdByAdminId,
                ]);

                $generated[] = $rec->code;
            }
        }, 3);

        return $generated;
    }

    private static function randomFromAlphabet(string $alphabet, int $length): string
    {
        $chars = [];
        $max = strlen($alphabet) - 1;
        for ($i=0; $i<$length; $i++) {
            $chars[] = $alphabet[random_int(0, $max)];
        }
        return implode('', $chars);
    }
}
