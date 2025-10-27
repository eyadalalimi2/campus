<?php

namespace App\Http\Controllers\Api\V1\Plans;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Common\PaginateRequest;
use App\Http\Resources\Api\V1\PlanResource;
use App\Models\Plan;
use App\Support\ApiResponse;
use App\Support\Cursor;
use App\Support\QueryFilters;
use Illuminate\Database\Eloquent\Builder;

final class PlansController extends Controller
{
    /**
     * GET /api/v1/plans
     * فلاتر: q(name/code), billing_cycle, is_active, currency, sort, limit, cursor
     * sort المسموح: name, -name, created_at, -created_at, id, -id
     */
    public function index(PaginateRequest $request)
    {
    $data   = $request->validated();
    $rawLimit = $data['limit'] ?? 'all';
    $isAll    = (is_string($rawLimit) && strtolower($rawLimit) === 'all');
    $limit    = $isAll ? null : min((int)$rawLimit, config('api.pagination.max', 50));
    $cursor = Cursor::decode($data['cursor'] ?? null);
    $offset = (int)($cursor['offset'] ?? 0);

        $query = Plan::query()
            ->select(['id','code','name','price_cents','currency','billing_cycle','is_active','created_at'])
            ->when(!empty($data['billing_cycle']), fn (Builder $q) => $q->where('billing_cycle', $data['billing_cycle']))
            ->when(!empty($data['currency']), fn (Builder $q) => $q->where('currency', $data['currency']))
            ->when(isset($data['is_active']), function (Builder $q) use ($data) {
                $b = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($b !== null) $q->where('is_active', $b ? 1 : 0);
            })
            ->when(!empty($data['q']), function (Builder $q) use ($data) {
                $term = trim($data['q']);
                $q->where(function (Builder $w) use ($term) {
                    $w->where('name','like',"%{$term}%")
                      ->orWhere('code','like',"%{$term}%");
                });
            });

        QueryFilters::applySorting($query, $data['sort'] ?? 'name', [
            'id','name','created_at'
        ]);

        $total = (clone $query)->count();
        if ($isAll) {
            $items = $query->get();
            $next  = null;
        } else {
            $items = $query->skip($offset)->take($limit)->get();
            $next = ($offset + $items->count() < $total) ? Cursor::encode(['offset'=>$offset + $items->count()]) : null;
        }

        return ApiResponse::ok(PlanResource::collection($items), ['count'=>$items->count(),'total'=>$total,'next_cursor'=>$next], ['next'=>$next]);
    }

    /**
     * GET /api/v1/plans/{id}
     */
    public function show(int $id)
    {
        $p = Plan::query()->find($id);
        if (!$p) return ApiResponse::error('NOT_FOUND','الخطة غير موجودة.',[],404);

        return ApiResponse::ok(new PlanResource($p));
    }
}
