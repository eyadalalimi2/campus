<?php

namespace App\Http\Controllers\Api\V1\Structure;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Common\PaginateRequest;
use App\Support\ApiResponse;
use App\Support\Cursor;
use App\Support\QueryFilters;
use App\Models\Country;
use Illuminate\Database\Eloquent\Builder;

final class CountriesController extends Controller
{
    /**
     * GET /api/v1/countries
     * فلاتر مدعومة: q, is_active (true/false), limit, cursor, sort
     * sort المسموح: name_ar, -name_ar, created_at, -created_at, id, -id
     */
    public function index(PaginateRequest $request)
    {
        $data   = $request->validated();
        $limit  = $data['limit'] ?? config('api.pagination.default', 20);
        $limit  = min((int)$limit, config('api.pagination.max', 50));
        $cursor = Cursor::decode($data['cursor'] ?? null);
        $offset = (int)($cursor['offset'] ?? 0);
        $q      = $data['q'] ?? null;

        $query = Country::query()
            ->select(['id', 'name_ar', 'iso2', 'phone_code', 'currency_code', 'is_active', 'created_at'])
            ->when(isset($data['is_active']), function (Builder $qq) use ($data) {
                $bool = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($bool !== null) $qq->where('is_active', $bool ? 1 : 0);
            })
            ->when($q, function (Builder $qq) use ($q) {
                $term = trim($q);
                $qq->where(function (Builder $w) use ($term) {
                    $w->where('name_ar', 'like', "%{$term}%")
                      ->orWhere('iso2', 'like', "%{$term}%")
                      ->orWhere('phone_code', 'like', "%{$term}%")
                      ->orWhere('currency_code', 'like', "%{$term}%");
                });
            });

        // الفرز المسموح
        QueryFilters::applySorting($query, $data['sort'] ?? 'name_ar', [
            'id', 'name_ar', 'created_at',
        ]);

        // Cursor pagination (offset-based)
        $total = (clone $query)->count();
        $items = $query->skip($offset)->take($limit)->get();

        $nextOffset  = $offset + $items->count();
        $nextCursor  = ($nextOffset < $total) ? Cursor::encode(['offset' => $nextOffset]) : null;

        $payload = $items->map(fn ($r) => [
            'id'            => (int)$r->id,
            'name'          => $r->name_ar,
            'iso2'          => $r->iso2,
            'phone_code'    => $r->phone_code,
            'currency_code' => $r->currency_code,
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
