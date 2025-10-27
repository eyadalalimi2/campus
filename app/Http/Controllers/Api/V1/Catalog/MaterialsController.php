<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Common\PaginateRequest;
use App\Http\Resources\Api\V1\MaterialResource;
use App\Models\Material;
use App\Support\ApiResponse;
use App\Support\Cursor;
use App\Support\QueryFilters;
use Illuminate\Database\Eloquent\Builder;

final class MaterialsController extends Controller
{
    /**
     * GET /api/v1/materials
     * فلاتر: q, scope(global|university), university_id, college_id, major_id, level, is_active, sort, limit, cursor
     * sort المسموح: name, -name, level, -level, id, -id, created_at, -created_at
     */
    public function index(PaginateRequest $request)
    {
    $data   = $request->validated();
    $rawLimit = $data['limit'] ?? 'all';
    $isAll    = (is_string($rawLimit) && strtolower($rawLimit) === 'all');
    $limit    = $isAll ? null : min((int)$rawLimit, config('api.pagination.max', 50));
    $cursor = Cursor::decode($data['cursor'] ?? null);
    $offset = (int)($cursor['offset'] ?? 0);

        $query = Material::query()
            ->select(['id','name','scope','university_id','college_id','major_id','level','is_active','created_at'])
            ->when(!empty($data['scope']),           fn (Builder $q) => $q->where('scope', $data['scope']))
            ->when(!empty($data['university_id']),  fn (Builder $q) => $q->where('university_id', (int)$data['university_id']))
            ->when(!empty($data['college_id']),     fn (Builder $q) => $q->where('college_id', (int)$data['college_id']))
            ->when(!empty($data['major_id']),       fn (Builder $q) => $q->where('major_id', (int)$data['major_id']))
            ->when(isset($data['level']),           fn (Builder $q) => $q->where('level', (int)$data['level']))
            ->when(isset($data['is_active']), function (Builder $q) use ($data) {
                $b = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($b !== null) $q->where('is_active', $b ? 1 : 0);
            })
            ->when(!empty($data['q']), fn (Builder $q) => $q->where('name','like','%'.trim($data['q']).'%'));

        QueryFilters::applySorting($query, $data['sort'] ?? 'name', [
            'id','name','level','created_at'
        ]);

        $total = (clone $query)->count();
        if ($isAll) {
            $items = $query->get();
            $nextCursor = null;
        } else {
            $items = $query->skip($offset)->take($limit)->get();
            $nextCursor = ($offset + $items->count() < $total) ? Cursor::encode(['offset'=>$offset + $items->count()]) : null;
        }

        return ApiResponse::ok(MaterialResource::collection($items), ['count'=>$items->count(),'total'=>$total,'next_cursor'=>$nextCursor], ['next'=>$nextCursor]);
    }

    /**
     * GET /api/v1/materials/{id}
     */
    public function show(int $id)
    {
        $m = Material::query()->find($id);
        if (!$m) return ApiResponse::error('NOT_FOUND','المادة غير موجودة.',[],404);

        return ApiResponse::ok(new MaterialResource($m));
    }
}
