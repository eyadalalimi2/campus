<?php

namespace App\Http\Controllers\Api\V1\Structure;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Common\PaginateRequest;
use App\Support\ApiResponse;
use App\Support\Cursor;
use App\Support\QueryFilters;
use App\Exceptions\Api\ApiException;
use App\Models\College;
use App\Models\University;
use Illuminate\Database\Eloquent\Builder;

final class CollegesController extends Controller
{
    /**
     * GET /api/v1/universities/{id}/colleges
     * فلاتر: q, is_active, limit, cursor, sort
     * sort المسموح: name, -name, created_at, -created_at, id, -id
     */
    public function byUniversity(PaginateRequest $request, int $id)
    {
        // تحقق وجود الجامعة
        if (!University::query()->whereKey($id)->exists()) {
            return ApiResponse::error('NOT_FOUND', 'الجامعة غير موجودة.', [], 404);
        }

        $data    = $request->validated();
        $limit   = $data['limit'] ?? config('api.pagination.default', 20);
        $limit   = min((int)$limit, config('api.pagination.max', 50));
        $cursor  = Cursor::decode($data['cursor'] ?? null);
        $offset  = (int)($cursor['offset'] ?? 0);
        $q       = $data['q'] ?? null;

        $query = College::query()
            ->select(['id', 'name', 'university_id', 'is_active', 'created_at'])
            ->where('university_id', $id)
            ->when(isset($data['is_active']), function (Builder $qq) use ($data) {
                $bool = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($bool !== null) $qq->where('is_active', $bool ? 1 : 0);
            })
            ->when($q, fn (Builder $qq) => $qq->where('name', 'like', '%' . trim($q) . '%'));

        QueryFilters::applySorting($query, $data['sort'] ?? 'name', [
            'id', 'name', 'created_at',
        ]);

        $total = (clone $query)->count();
        $items = $query->skip($offset)->take($limit)->get();

        $nextOffset  = $offset + $items->count();
        $nextCursor  = ($nextOffset < $total) ? Cursor::encode(['offset' => $nextOffset]) : null;

        $payload = $items->map(fn ($r) => [
            'id'            => (int)$r->id,
            'name'          => $r->name,
            'university_id' => (int)$r->university_id,
            'is_active'     => (bool)$r->is_active,
            'created_at'    => $r->created_at,
        ]);

        return ApiResponse::ok(
            $payload,
            ['count' => $items->count(), 'total' => $total, 'next_cursor' => $nextCursor],
            ['next'  => $nextCursor]
        );
    }
}
