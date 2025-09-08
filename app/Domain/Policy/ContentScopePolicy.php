<?php

namespace App\Domain\Policy;

use App\Models\User;

/**
 * سياسة نطاق المحتوى:
 * - الطالب المرتبط بجامعة: يرى المحتوى العام (Assets) + المحتوى الخاص (Contents) لجامعته/كليته/تخصصه.
 * - الطالب غير المرتبط بجامعة: يرى المحتوى العام (Assets) فقط، وفق تخصصه/كليته (إن وُجدا).
 */
final class ContentScopePolicy
{
    /**
     * إرجاع بنية موحّدة تُستخدم في الواجهات (VisibilityController, Feed, ...).
     *
     * @return array{
     *   linked_to_university: bool,
     *   allowed_sources: array<int,string>,
     *   scope: array{university_id:?int, college_id:?int, major_id:?int}
     * }
     */
    public function evaluate(User $user): array
    {
        $linked = !empty($user->university_id);

        return [
            'linked_to_university' => $linked,
            'allowed_sources'      => $linked ? ['assets', 'contents'] : ['assets'],
            'scope'                => [
                'university_id' => $linked ? (int) $user->university_id : null,
                'college_id'    => $user->college_id ? (int) $user->college_id : null,
                'major_id'      => $user->major_id ? (int) $user->major_id : null,
            ],
        ];
    }
}
