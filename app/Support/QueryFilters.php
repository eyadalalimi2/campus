<?php
namespace App\Support;

use Illuminate\Database\Query\Builder;

final class QueryFilters
{
    /**
     * يطبّق ترتيبًا ديناميكيًا آمنًا بالاعتماد على قائمة/خريطة أعمدة مسموحة.
     *
     * @param Builder     $q       استعلام Query Builder
     * @param string|null $sort    سلسلة فرز مثل: "created_at,-id" أو "-published_at"
     * @param array       $allowed قائمة أعمدة مسموحة:
     *                             - كمصفوفة: ['created_at','id']
     *                             - أو خريطة: ['created_at'=>'assets.created_at','id'=>'assets.id']
     */
    public static function applySorting(Builder $q, ?string $sort, array $allowed): void
    {
        if (!$sort) return;

        // كشف ما إذا كانت قائمة الأعمدة خريطة تحويل (associative)
        $isMap = static::isAssoc($allowed);

        $applied = [];
        foreach (explode(',', $sort) as $token) {
            $token = trim($token);
            if ($token === '') continue;

            $dir = 'asc';
            if (str_starts_with($token, '-')) {
                $dir = 'desc';
                $token = substr($token, 1);
            }

            // resolve column
            $col = null;
            if ($isMap && array_key_exists($token, $allowed)) {
                $col = $allowed[$token];
            } elseif (!$isMap && in_array($token, $allowed, true)) {
                $col = $token;
            }

            if (!$col) continue;                  // غير مسموح
            if (isset($applied[$col])) continue;  // لا نكرر نفس الحقل

            $q->orderBy($col, $dir);
            $applied[$col] = true;
        }
    }

    private static function isAssoc(array $arr): bool
    {
        if ($arr === []) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
