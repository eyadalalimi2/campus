<?php

namespace App\Http\Controllers\Api\V1\Content;

use App\Domain\Policy\ContentScopePolicy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Common\PaginateRequest;
use App\Http\Resources\Api\V1\ContentResource;
use App\Models\Content;
use App\Support\ApiResponse;
use App\Support\Cursor;
use App\Support\QueryFilters;
use App\Exceptions\Api\ApiException;
use Illuminate\Database\Eloquent\Builder;

final class ContentsController extends Controller
{
    public function __construct(private ContentScopePolicy $policy) {}

    /**
     * GET /api/v1/contents
     * مخصص للطلاب المرتبطين بجامعة فقط (الميدلوير u-scope يفرض ذلك، ومع ذلك نتحقق هنا أيضاً)
     * فلاتر: q(title/description), material_id, status=published, sort, limit, cursor
     * sort المسموح: published_at, -published_at, created_at, -created_at, id, -id
     */
    public function index(PaginateRequest $request)
    {
        $user = auth()->user();
        if (!$user) throw new ApiException('UNAUTHORIZED','الرجاء تسجيل الدخول.',401);

        $vis = $this->policy->evaluate($user);
        if (!$vis['linked_to_university']) {
            return ApiResponse::error('FORBIDDEN','هذا القسم متاح فقط للطلاب المرتبطين بجامعة.',[],403);
        }

        $data   = $request->validated();
        $limit  = min((int)($data['limit'] ?? config('api.pagination.default', 20)), config('api.pagination.max', 50));
        $cursor = Cursor::decode($data['cursor'] ?? null);
        $offset = (int)($cursor['offset'] ?? 0);

        $query = Content::query()
            ->select(['id','title','description','type','source_url','file_path','university_id','college_id','major_id','material_id','doctor_id','status','is_active','published_at','created_at'])
            ->where('status','published')->where('is_active',1)
            ->whereNotNull('published_at')->where('published_at','<=',now())
            // نطاق الجامعة إلزامي على المحتوى الخاص
            ->where('university_id', $user->university_id)
            ->when($user->college_id, fn (Builder $q) => $q->where(function (Builder $w) use ($user) {
                $w->whereNull('college_id')->orWhere('college_id', $user->college_id);
            }))
            ->when($user->major_id, fn (Builder $q) => $q->where(function (Builder $w) use ($user) {
                $w->whereNull('major_id')->orWhere('major_id', $user->major_id);
            }))
            ->when(!empty($data['material_id']), fn (Builder $q) => $q->where('material_id',(int)$data['material_id']))
            ->when(!empty($data['q']), function (Builder $q) use ($data) {
                $term = trim($data['q']);
                $q->where(function (Builder $w) use ($term) {
                    $w->where('title','like',"%{$term}%")
                      ->orWhere('description','like',"%{$term}%");
                });
            });

        QueryFilters::applySorting($query, $data['sort'] ?? '-published_at', [
            'id','created_at','published_at'
        ]);

        $total = (clone $query)->count();
        $items = $query->skip($offset)->take($limit)->get();

        $next = ($offset + $items->count() < $total) ? Cursor::encode(['offset'=>$offset + $items->count()]) : null;

        return ApiResponse::ok(ContentResource::collection($items), ['count'=>$items->count(),'total'=>$total,'next_cursor'=>$next], ['next'=>$next]);
    }

    /**
     * GET /api/v1/contents/{id}
     */
    public function show(int $id)
    {
        $user = auth()->user();
        if (!$user) throw new ApiException('UNAUTHORIZED','الرجاء تسجيل الدخول.',401);

        $vis = $this->policy->evaluate($user);
        if (!$vis['linked_to_university']) {
            return ApiResponse::error('FORBIDDEN','هذا القسم متاح فقط للطلاب المرتبطين بجامعة.',[],403);
        }

        $c = Content::query()
            ->where('status','published')->where('is_active',1)
            ->whereNotNull('published_at')->where('published_at','<=',now())
            ->where('university_id',$user->university_id)
            ->when($user->college_id, fn (Builder $q) => $q->where(function (Builder $w) use ($user) {
                $w->whereNull('college_id')->orWhere('college_id',$user->college_id);
            }))
            ->when($user->major_id, fn (Builder $q) => $q->where(function (Builder $w) use ($user) {
                $w->whereNull('major_id')->orWhere('major_id',$user->major_id);
            }))
            ->find($id);

        if (!$c) return ApiResponse::error('NOT_FOUND','المحتوى غير موجود أو غير مصرح.',[],404);

        return ApiResponse::ok(new ContentResource($c));
    }
}
