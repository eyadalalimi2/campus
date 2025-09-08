<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ActivationCodeGenerator
{
    /**
     * توليد مجموعة فريدة من الأكواد الرقمية بطول $length
     * مع اختيار بادئة prefix (اختياري).
     */
    public static function generateUniqueSet(int $count, int $length = 10, ?string $prefix = null): array
    {
        $length = max(4, min(24, $length));
        $result = [];

        // اجلب الأكواد الموجودة مسبقًا بنفس البادئة لتفادي التصادم
        $existing = [];
        if ($prefix) {
            $existing = DB::table('activation_codes')
                ->where('code','like',$prefix.'%')
                ->pluck('code')->all();
        } else {
            $existing = DB::table('activation_codes')->pluck('code')->all();
        }
        $existing = array_flip($existing);

        while (count($result) < $count) {
            // أنشئ دفعة مؤقتة (حتى 1000 في المرة)
            $need = $count - count($result);
            $batchSize = min(1000, $need);

            for ($i=0; $i<$batchSize; $i++) {
                $digits = self::randomDigits($length - ($prefix ? mb_strlen($prefix) : 0));
                $code = ($prefix ? $prefix : '') . $digits;

                if (!isset($existing[$code]) && !isset($result[$code])) {
                    $result[$code] = true;
                }
            }
        }

        return array_keys($result);
    }

    /**
     * اصنع صفوف الإدراج bulk insert مع الحقول المشتركة.
     */
    public static function buildInsertRows(array $codes, array $common = []): array
    {
        $now = now();
        $rows = [];
        foreach ($codes as $code) {
            $rows[] = array_merge([
                'code'       => $code,
                'created_at' => $now,
                'updated_at' => $now,
            ], $common);
        }
        return $rows;
    }

    protected static function randomDigits(int $len): string
    {
        if ($len <= 0) return '';
        // رقم أولي بدون أصفار مفقودة: نبني سلسلة أرقام مباشرة
        $s = '';
        for ($i=0;$i<$len;$i++) {
            $s .= (string) random_int(0,9);
        }
        return $s;
    }
}
