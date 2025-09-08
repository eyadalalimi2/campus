<?php
namespace App\Support;

final class Cursor
{
    public static function encode(array $state): string
    {
        $json = json_encode($state, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return rtrim(strtr(base64_encode($json), '+/', '-_'), '=');
    }

    public static function decode(?string $cursor): array
    {
        if (!$cursor) return [];

        // استرجاع الصيغة القياسية وإضافة padding المفقود
        $b64 = strtr($cursor, '-_', '+/');
        $pad = strlen($b64) % 4;
        if ($pad) {
            $b64 .= str_repeat('=', 4 - $pad);
        }

        $json = base64_decode($b64, true);
        if ($json === false) return [];

        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }
}
