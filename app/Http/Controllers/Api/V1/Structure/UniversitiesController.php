<?php

namespace App\Http\Controllers\Api\V1\Structure;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Common\PaginateRequest;
use App\Support\ApiResponse;
use App\Support\Cursor;
use App\Support\QueryFilters;
use App\Models\University;
use Illuminate\Database\Eloquent\Builder;

final class UniversitiesController extends Controller
{
    /**
     * GET /api/v1/universities
     * فلاتر: q, country_id, is_active, limit, cursor, sort
     * sort المسموح: name, -name, created_at, -created_at, id, -id
     */
    public function index(PaginateRequest $request)
    {
        $data    = $request->validated();
        $limit   = $data['limit'] ?? config('api.pagination.default', 20);
        $limit   = min((int)$limit, config('api.pagination.max', 50));
        $cursor  = Cursor::decode($data['cursor'] ?? null);
        $offset  = (int)($cursor['offset'] ?? 0);
        $q       = $data['q'] ?? null;

        $query = University::query()
            ->select(['id', 'name', 'address', 'country_id', 'phone', 'logo_path', 'is_active', 'created_at'])
            ->when(isset($data['country_id']), fn (Builder $qq) => $qq->where('country_id', (int)$data['country_id']))
            ->when(isset($data['is_active']), function (Builder $qq) use ($data) {
                $bool = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($bool !== null) $qq->where('is_active', $bool ? 1 : 0);
            })
            ->when($q, function (Builder $qq) use ($q) {
                $term = trim($q);
                $qq->where(function (Builder $w) use ($term) {
                    $w->where('name', 'like', "%{$term}%")
                      ->orWhere('address', 'like', "%{$term}%")
                      ->orWhere('phone', 'like', "%{$term}%");
                });
            });

        QueryFilters::applySorting($query, $data['sort'] ?? 'name', [
            'id', 'name', 'created_at',
        ]);

        $total = (clone $query)->count();
        $items = $query->skip($offset)->take($limit)->get();

        $nextOffset  = $offset + $items->count();
        $nextCursor  = ($nextOffset < $total) ? Cursor::encode(['offset' => $nextOffset]) : null;

        $payload = $items->map(fn ($r) => [
            'id'         => (int)$r->id,
            'name'       => $r->name,
            'address'    => $r->address,
            'country_id' => $r->country_id ? (int)$r->country_id : null,
            'phone'      => $r->phone,
            'logo_path'  => $r->logo_path,
            'is_active'  => (bool)$r->is_active,
            'created_at' => $r->created_at,
        ]);

        return ApiResponse::ok(
            $payload,
            ['count' => $items->count(), 'total' => $total, 'next_cursor' => $nextCursor],
            ['next'  => $nextCursor]
        );
    }
}
