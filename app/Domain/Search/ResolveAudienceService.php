<?php

namespace App\Domain\Search;

use Illuminate\Database\Eloquent\Builder as EBuilder;
use Illuminate\Database\Query\Builder as QBuilder;

/**
 * سياسة استهداف جمهور الأصول (Assets):
 * - إن كان هناك تخصص مستهدف (major): نعرض
 *     (أ) الأصول المطابقة مباشرة عبر asset_audiences.major_id
 *   أو (ب) الأصول العامة (بدون جمهور) التي يطابق برنامجها برنامج التخصص عبر major_program.
 * - إن لم يوجد تخصص: نعرض فقط الأصول العامة (بدون جمهور).
 * - فلاتر اختيارية مباشرة: discipline_id / program_id.
 *
 * ملاحظة تنفيذية:
 * يُفضّل استدعاء الدالة داخل where(function ($q) { ... }) في الاستعلام الأعلى.
 */
final class ResolveAudienceService
{
    /**
     * @param EBuilder|QBuilder $q           استعلام على جدول الأصول (قد يكون له ألياس)
     * @param int|null          $userMajorId تخصص المستخدم (إن وجد)
     * @param int|null          $majorId     تخصص مُمرّر صراحة (يتقدم على userMajorId)
     * @param int|null          $programId   فلتر مباشر على برنامج الأصل (اختياري)
     * @param int|null          $disciplineId فلتر مباشر على المجال/المسار (اختياري)
     * @param string            $tableAlias  اسم الجدول/الألياس المستخدم في الاستعلام الأعلى (الافتراضي 'assets')
     */
    public function applyToAssets(
        EBuilder|QBuilder $q,
        ?int $userMajorId = null,
        ?int $majorId = null,
        ?int $programId = null,
        ?int $disciplineId = null,
        string $tableAlias = 'assets'
    ): void {
        // فلاتر مباشرة (اختيارية)
        if ($disciplineId) {
            $q->where("{$tableAlias}.discipline_id", (int) $disciplineId);
        }
        if ($programId) {
            $q->where("{$tableAlias}.program_id", (int) $programId);
        }

        $targetMajor = $majorId ?? $userMajorId;

        // تطبيق منطق الجمهور
        $q->where(function ($w) use ($targetMajor, $tableAlias) {
            if ($targetMajor) {
                // (أ) تطابق مباشر عبر جمهور الأصل
                $w->whereExists(function ($sub) use ($targetMajor, $tableAlias) {
                    $sub->from('asset_audiences as aa')
                        ->whereColumn('aa.asset_id', "{$tableAlias}.id")
                        ->where('aa.major_id', (int) $targetMajor);
                })
                // (ب) أو أصل عام (بدون جمهور) وبرنامجه يطابق برنامج التخصص عبر major_program
                ->orWhere(function ($or) use ($targetMajor, $tableAlias) {
                    $or->whereNotExists(function ($noAud) use ($tableAlias) {
                        $noAud->from('asset_audiences as aa')
                              ->whereColumn('aa.asset_id', "{$tableAlias}.id");
                    })->whereExists(function ($mp) use ($targetMajor, $tableAlias) {
                        $mp->from('major_program as mp')
                          ->whereColumn('mp.program_id', "{$tableAlias}.program_id")
                          ->where('mp.major_id', (int) $targetMajor);
                    });
                });
            } else {
                // لا يوجد تخصص => نعرض فقط الأصول العامة (بدون جمهور محدد)
                $w->whereNotExists(function ($noAud) use ($tableAlias) {
                    $noAud->from('asset_audiences as aa')
                          ->whereColumn('aa.asset_id', "{$tableAlias}.id");
                });
            }
        });
    }
}
