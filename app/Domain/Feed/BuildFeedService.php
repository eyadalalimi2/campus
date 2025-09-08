<?php

namespace App\Domain\Feed;

use Illuminate\Support\Facades\DB;
use App\Support\Cursor;

final class BuildFeedService
{
    /**
     * @param array{
     *   user_id?: int,
     *   university_id?: int|null,
     *   college_id?: int|null,
     *   major_id?: int|null,
     *   limit: int,
     *   cursor?: array{ts?: int, id?: int}
     * } $ctx
     *
     * @return array{0: array<int,array>, 1: ?string} [$items, $nextCursor]
     */
    public function build(array $ctx): array
    {
        $limit = max(1, (int)($ctx['limit'] ?? 20));
        $u     = $ctx['university_id'] ?? null;
        $c     = $ctx['college_id']    ?? null;
        $m     = $ctx['major_id']      ?? null;

        $state   = $ctx['cursor'] ?? [];
        $afterTs = isset($state['ts']) ? (int)$state['ts'] : null;
        $afterId = isset($state['id']) ? (int)$state['id'] : null;

        // ========= ASSETS (Public) =========
        // - منشورة وفعّالة ومؤرخة حتى الآن
        // - جمهور:
        //   * إن لم يوجد major => فقط العامة (بدون جمهور)
        //   * إن وجد major     => العامة OR جمهور يطابق الـ major
        $assetsQ = DB::table('assets as a')
            ->select(
                'a.id','a.title','a.description','a.published_at','a.category',
                'a.video_url','a.file_path','a.external_url','a.material_id',
                'a.discipline_id','a.program_id'
            )
            ->where('a.status', 'published')
            ->where('a.is_active', 1)
            ->whereNotNull('a.published_at')
            ->where('a.published_at', '<=', now());

        $assetsQ->where(function ($w) use ($m) {
            // لا جمهور = أصل عام
            $w->whereNotExists(function ($q) {
                $q->from('asset_audiences as aa')
                  ->whereColumn('aa.asset_id', 'a.id');
            });

            // إضافة الاستهداف إذا كان للطالب تخصص
            if ($m) {
                $w->orWhereExists(function ($q) use ($m) {
                    $q->from('asset_audiences as aa')
                      ->whereColumn('aa.asset_id', 'a.id')
                      ->where('aa.major_id', (int)$m);
                });
            }
        });

        // ترتيب مستقر
        $assetsQ->orderBy('a.published_at', 'desc')
                ->orderBy('a.id', 'desc')
                // نافذة مضاعفة ثم نقص لاحقًا بعد الدمج
                ->limit($limit * 2);

        $assets = $assetsQ->get()
            ->map(function ($a) {
                return [
                    'kind'         => 'asset',
                    'id'           => (int)$a->id,
                    'title'        => $a->title,
                    'description'  => $a->description,
                    'published_at' => (string)$a->published_at,
                    'media'        => [
                        'video_url'   => $a->video_url,
                        'file_path'   => $a->file_path,
                        'external_url'=> $a->external_url,
                    ],
                    'material_id'  => $a->material_id ? (int)$a->material_id : null,
                    'discipline_id'=> $a->discipline_id ? (int)$a->discipline_id : null,
                    'program_id'   => $a->program_id ? (int)$a->program_id : null,
                ];
            })
            ->all();

        // ========= CONTENTS (Private per University scope) =========
        $contents = [];
        if ($u) {
            $contentsQ = DB::table('contents as c')
                ->select(
                    'c.id','c.title','c.description','c.published_at','c.created_at','c.type',
                    'c.source_url','c.file_path','c.material_id','c.university_id','c.college_id','c.major_id'
                )
                ->where('c.status', 'published')
                ->where('c.is_active', 1)
                ->whereNotNull('c.published_at')
                ->where('c.published_at', '<=', now())
                ->where('c.university_id', (int)$u);

            // college: إما عام (NULL) أو يساوي كلية الطالب
            if ($c) {
                $contentsQ->where(function ($q) use ($c) {
                    $q->whereNull('c.college_id')
                      ->orWhere('c.college_id', (int)$c);
                });
            }

            // major: إما عام (NULL) أو يساوي تخصص الطالب
            if ($m) {
                $contentsQ->where(function ($q) use ($m) {
                    $q->whereNull('c.major_id')
                      ->orWhere('c.major_id', (int)$m);
                });
            }

            // ترتيب مستقر (باستخدام published_at وإذا لم تتوفر fallback لـ created_at فقط للتحديد الداخلي)
            $contentsQ->orderByRaw('COALESCE(c.published_at, c.created_at) DESC')
                      ->orderBy('c.id', 'desc')
                      ->limit($limit * 2);

            $contents = $contentsQ->get()
                ->map(function ($x) {
                    $pub = $x->published_at ?: $x->created_at;
                    return [
                        'kind'         => 'content',
                        'id'           => (int)$x->id,
                        'title'        => $x->title,
                        'description'  => $x->description,
                        'published_at' => (string)$pub,
                        'media'        => [
                            'source_url' => $x->source_url,
                            'file_path'  => $x->file_path,
                        ],
                        'material_id'  => $x->material_id ? (int)$x->material_id : null,
                        'university_id'=> (int)$x->university_id,
                        'college_id'   => $x->college_id ? (int)$x->college_id : null,
                        'major_id'     => $x->major_id ? (int)$x->major_id : null,
                    ];
                })
                ->all();
        }

        // ========= MERGE + ORDER =========
        $merged = array_merge($assets, $contents);

        usort($merged, function (array $a, array $b) {
            $ta = strtotime($a['published_at'] ?? '1970-01-01 00:00:00');
            $tb = strtotime($b['published_at'] ?? '1970-01-01 00:00:00');

            // الأحدث أولًا
            if ($ta === $tb) {
                // في حال تساوي الوقت نرتّب بالمعرّف نزولياً (الأكبر أولًا)
                return $b['id'] <=> $a['id'];
            }
            return $tb <=> $ta;
        });

        // ========= CURSOR FILTER (الأقدم من المؤشر) =========
        if ($afterTs || $afterId) {
            $merged = array_values(array_filter($merged, function (array $row) use ($afterTs, $afterId) {
                $ts = strtotime($row['published_at'] ?? '1970-01-01 00:00:00');
                // نعرض الصفوف الأقدم من المؤشر
                if ($afterTs !== null && $ts < $afterTs) {
                    return true;
                }
                if ($afterTs !== null && $ts === $afterTs) {
                    return ($afterId !== null) ? ($row['id'] < $afterId) : false;
                }
                // إن كان ts أكبر من afterTs (أحدث) نتجاهله في صفحة "التالي"
                return false;
            }));
        }

        // ========= SLICE + NEXT CURSOR =========
        $slice = array_slice($merged, 0, $limit);

        $next = null;
        if (count($merged) > $limit && !empty($slice)) {
            $last = end($slice); // الأقدم في الصفحة الحالية
            $next = Cursor::encode([
                'ts' => strtotime($last['published_at'] ?? '1970-01-01 00:00:00'),
                'id' => (int)$last['id'],
            ]);
        }

        return [$slice, $next];
    }
}
