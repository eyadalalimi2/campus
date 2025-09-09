<?php
namespace App\Support;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

final class QueryFilters
{
    /**
     * يطبّق ترتيب ديناميكي آمن على استعلام (يدعم كلًا من Eloquent و Query Builder).
     *
     * @param QueryBuilder|EloquentBuilder $q
     * @param string|null $sort   مثال: "name,-created_at"
     * @param array $allowed      الأعمدة المسموح ترتيبها فقط
     */
    public static function applySorting(QueryBuilder|EloquentBuilder $q, ?string $sort, array $allowed): void
    {
        if (!$sort) return;

        foreach (explode(',', $sort) as $s) {
            $dir = 'asc';
            $col = $s;

            if (str_starts_with($s, '-')) {
                $dir = 'desc';
                $col = substr($s, 1);
            }

            if (in_array($col, $allowed, true)) {
                $q->orderBy($col, $dir);
            }
        }
    }
}
