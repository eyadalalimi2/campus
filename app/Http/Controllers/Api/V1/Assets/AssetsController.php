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
use Illuminate\Support\Facades\DB;

final class AssetsController extends Controller
{
    public function __construct(
        private ContentScopePolicy $policy,
        private ResolveAudienceService $audience
    ) {}

    /**
     * GET /api/v1/assets
     * يُظهر المحتوى العام (Assets) وفق الجمهور:
     * - سجلات بدون جمهور (global) للجميع
     * - أو المطابقة لتخصص المستخدم عبر جدول asset_audiences (major_id)
     * فلاتر إضافية: q (title/description), category, material_id, status=published, sort, limit, cursor
     * sort المسموح: published_at, -published_at, created_at, -created_at, id, -id
     */
    public function index(PaginateRequest $request)
    {
        $data   = $request->validated();
        $limit  = min((int)($data['limit'] ?? config('api.pagination.default', 20)), config('api.pagination.max', 50));
        $cursor = Cursor::decode($data['cursor'] ?? null);
        $offset = (int)($cursor['offset'] ?? 0);

        $user   = auth()->user();
        $vis    = $user ? $this->policy->evaluate($user) : ['linked_to_university'=>false,'scope'=>['major_id'=>null]];

        $query = Asset::query()
            ->select(['assets.id','assets.category','assets.title','assets.description','assets.status','assets.published_at','assets.is_active','assets.material_id','assets.discipline_id','assets.program_id','assets.device_id','assets.doctor_id','assets.created_at'])
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

        // الجمهور: إظهار الأصول العامة (لا توجد audience) أو المطابقة لِـ major_id للمستخدم
        $majorId = $vis['scope']['major_id'] ?? null;
        $query->where(function (Builder $w) use ($majorId) {
            $w->whereDoesntHave('audiences'); // لا جمهور = عالمي
            if ($majorId) {
                $w->orWhereHas('audiences', fn ($aa) => $aa->where('major_id', (int)$majorId));
            }
        });

        QueryFilters::applySorting($query, $data['sort'] ?? '-published_at', [
            'id','created_at','published_at'
        ]);

        $total = (clone $query)->count();
        $items = $query->skip($offset)->take($limit)->get();

        $next = ($offset + $items->count() < $total) ? Cursor::encode(['offset'=>$offset + $items->count()]) : null;

        return ApiResponse::ok(AssetResource::collection($items), ['count'=>$items->count(),'total'=>$total,'next_cursor'=>$next], ['next'=>$next]);
    }

    /**
     * GET /api/v1/assets/{id}
     */
    public function show(int $id)
    {
        $asset = Asset::query()
            ->with(['audiences'])
            ->where('status','published')->where('is_active',1)
            ->whereNotNull('published_at')->where('published_at','<=', now())
            ->find($id);

        if (!$asset) return ApiResponse::error('NOT_FOUND','العنصر غير موجود أو غير منشور.',[],404);

        // تحقق الجمهور: إن كان للأصل جمهور محدد يجب أن يطابق تخصص المستخدم
        $user = auth()->user();
        if ($asset->audiences?->count()) {
            $majorId = $user?->major_id;
            $allowed = $majorId ? DB::table('asset_audiences')->where('asset_id',$asset->id)->where('major_id',$majorId)->exists() : false;
            if (!$allowed) return ApiResponse::error('FORBIDDEN','غير مصرح بعرض هذا المحتوى.',[],403);
        }

        return ApiResponse::ok(new AssetResource($asset));
    }
}
