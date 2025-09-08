<?php

namespace App\Support;

/**
 * مولدات معرفات/سلاسل قصيرة للاستخدام العام (آمنة وعملية).
 * لا يعتمد على حزم خارجية.
 */
final class StrId
{
    /** أبجدية Base62 للاستخدام في nano() */
    private const ALPHABET = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

    /**
     * UUID v4 (نصي) — متوافق على نطاق واسع.
     */
    public static function uuid(): string
    {
        $data = random_bytes(16);
        // ضبط إصدارة UUID v4 والمتغير
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * سلسلة Base62 قصيرة بطول معيّن (افتراضي 12).
     * مناسبة للرموز/المفاتيح القصيرة غير الحساسة لحالة الأحرف.
     */
    public static function nano(int $length = 12): string
    {
        $len = max(1, min($length, 64));
        $out = '';
        $alpha = self::ALPHABET;
        $max = strlen($alpha) - 1;

        for ($i = 0; $i < $len; $i++) {
            $idx = random_int(0, $max);
            $out .= $alpha[$idx];
        }
        return $out;
    }

    /**
     * مُعرّف قابل للترتيب زمنيًا: TIMESTAMP(base36) + "-" + Base62(6)
     * مفيد كمفاتيح خارجية أو أسماء ملفات.
     */
    public static function ordered(): string
    {
        $ts = base_convert((string)time(), 10, 36);
        return $ts . '-' . self::nano(6);
    }
    /**
     * مولّد رمز تحقق رقمي (OTP)
     * @param int $length عدد الخانات (افتراضي 6)
     */
    public static function otp(int $length = 6): string
    {
        $len = max(4, min($length, 10)); // السماح من 4 إلى 10 خانات
        $out = '';
        for ($i = 0; $i < $len; $i++) {
            $out .= random_int(0, 9);
        }
        return $out;
    }
}
