<?php

namespace App\Http\Controllers\Api\V1\Assets;

use App\Domain\Policy\ContentScopePolicy;
use App\Domain\Search\ResolveAudienceService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Common\PaginateRequest;
use App\Http\Resources\Api\V1\AssetResource;
use App\Models\Asset;
use App\Support\ApiResponse;
use App\Support\Cursor;
use App\Support\QueryFilters;
use Illuminate\Database\Eloquent\Builder;

final class AssetsController extends Controller
{
    public function __construct(
        private ContentScopePolicy $policy,
        private ResolveAudienceService $audience
    ) {}

    /**
     * GET /api/v1/assets
     *
     * يُظهر المحتوى العام (Assets) وفق الجمهور العام:
     * - الأصول "العالمية" (لا جمهور عام مرتبط) للجميع.
     * - أو الأصول المطابقة لـ public_major_id للمستخدم (مباشرًا أو عبر mapping من Major الجامعي).
     *
     * فلاتر: q (title/description), category, material_id, status=published, sort, limit, cursor
     * sort: published_at, -published_at, created_at, -created_at, id, -id
     */
    public function index(PaginateRequest $request)
    {
        $data   = $request->validated();
        $limit  = min((int)($data['limit'] ?? config('api.pagination.default', 20)), config('api.pagination.max', 50));
        $cursor = Cursor::decode($data['cursor'] ?? null);
        $offset = (int)($cursor['offset'] ?? 0);

        $user   = auth()->user();
        // سياسة الرؤية العامة (قد تحتاج لاحقًا للمحتوى المؤسسي؛ نُبقيها كما هي)
        $vis    = $user ? $this->policy->evaluate($user) : ['linked_to_university'=>false,'scope'=>[]];

        // فلتر اختياري يُمرّره العميل صراحة
        $filterPublicMajorId = isset($data['public_major_id']) ? (int)$data['public_major_id'] : 0;

        // استنتاج public_major للمستخدم إن لم يمرر فلتر مباشر (يتطلب mapping: user->major->public_major_id)
        if (! $filterPublicMajorId && $user && $user->major && $user->major->public_major_id) {
            $filterPublicMajorId = (int) $user->major->public_major_id;
        }

        $query = Asset::query()
            ->select([
                'assets.id','assets.category','assets.title','assets.description','assets.status',
                'assets.published_at','assets.is_active','assets.material_id','assets.discipline_id',
                'assets.program_id','assets.device_id','assets.doctor_id','assets.created_at'
            ])
            ->where('assets.status','published')
            ->where('assets.is_active',1)
            ->whereNotNull('assets.published_at')
            ->where('assets.published_at','<=', now())
            ->when(!empty($data['category']), fn (Builder $q) => $q->where('assets.category',$data['category']))
            ->when(!empty($data['material_id']), fn (Builder $q) => $q->where('assets.material_id',(int)$data['material_id']))
            ->when(!empty($data['q']), function (Builder $q) use ($data) {
                $term = trim($data['q']);
                $q->where(function (Builder $w) use ($term) {
                    $w->where('assets.title', 'like', "%{$term}%")
                      ->orWhere('assets.description', 'like', "%{$term}%");
                });
            });

        /**
         * الجمهور العام:
         * - whereDoesntHave('publicMajors') => أصل عالمي
         * - orWhereHas('publicMajors', id = $filterPublicMajorId) عند وجود معرف
         */
        $query->where(function (Builder $w) use ($filterPublicMajorId) {
            $w->whereDoesntHave('publicMajors');
            if ($filterPublicMajorId) {
                $w->orWhereHas('publicMajors', fn ($qq) => $qq->where('public_majors.id', $filterPublicMajorId));
            }
        });

        QueryFilters::applySorting($query, $data['sort'] ?? '-published_at', [
            'id','created_at','published_at'
        ]);

        $total = (clone $query)->count();
        $items = $query->skip($offset)->take($limit)->get();

        $next = ($offset + $items->count() < $total) ? Cursor::encode(['offset'=>$offset + $items->count()]) : null;

        return ApiResponse::ok(
            AssetResource::collection($items),
            ['count'=>$items->count(),'total'=>$total,'next_cursor'=>$next],
            ['next'=>$next]
        );
    }

    /**
     * GET /api/v1/assets/{id}
     *
     * يُرجع الأصل إذا كان:
     * - عالميًا (لا جمهور عام مرتبط)
     * - أو يطابق public_major للمستخدم/الفلتر
     */
    public function show(int $id)
    {
        $asset = Asset::query()
            ->with(['publicMajors']) // علاقات الجمهور العام فقط
            ->where('status','published')->where('is_active',1)
            ->whereNotNull('published_at')->where('published_at','<=', now())
            ->find($id);

        if (! $asset) {
            return ApiResponse::error('NOT_FOUND','العنصر غير موجود أو غير منشور.',[],404);
        }

        // إن كان للأصل جمهور عام مقيّد، تحقّق المطابقة
        if ($asset->publicMajors->isNotEmpty()) {
            $user = auth()->user();
            // يسمح العميل بتمرير public_major_id كفلتر (مثلاً تطبيق جوّال يحدد اهتمام المستخدم)
            $publicMajorId = (int) request()->query('public_major_id', 0);

            // استنتج من المستخدم إن لم يُمرّر فلتر مباشر
            if (! $publicMajorId && $user && $user->major && $user->major->public_major_id) {
                $publicMajorId = (int) $user->major->public_major_id;
            }

            $allowed = $publicMajorId
                ? $asset->publicMajors->contains('id', $publicMajorId)
                : false;

            if (! $allowed) {
                return ApiResponse::error('FORBIDDEN','غير مصرح بعرض هذا المحتوى.',[],403);
            }
        }

        return ApiResponse::ok(new AssetResource($asset));
    }
}
