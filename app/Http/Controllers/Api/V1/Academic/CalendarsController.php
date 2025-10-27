<?php

namespace App\Http\Controllers\Api\V1\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Common\PaginateRequest;
use App\Support\ApiResponse;
use App\Support\Cursor;
use App\Support\QueryFilters;
use App\Models\AcademicCalendar;
use Illuminate\Database\Eloquent\Builder;

final class CalendarsController extends Controller
{
    /**
     * GET /api/v1/calendars
     * فلاتر: university_id, is_active, from(YYYY-MM-DD), to(YYYY-MM-DD), q (على year_label), sort, limit, cursor
     * sort المسموح: year_label, -year_label, starts_on, -starts_on, id, -id, created_at, -created_at
     */
    public function index(PaginateRequest $request)
    {
    $data   = $request->validated();
    $rawLimit = $data['limit'] ?? 'all';
    $isAll    = (is_string($rawLimit) && strtolower($rawLimit) === 'all');
    $limit    = $isAll ? null : min((int)$rawLimit, config('api.pagination.max', 50));
    $cursor = Cursor::decode($data['cursor'] ?? null);
    $offset = (int)($cursor['offset'] ?? 0);

        $query = AcademicCalendar::query()
            ->select(['id','university_id','year_label','starts_on','ends_on','is_active','created_at'])
            ->when(!empty($data['university_id']), fn (Builder $q) => $q->where('university_id', (int)$data['university_id']))
            ->when(isset($data['is_active']), function (Builder $q) use ($data) {
                $b = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($b !== null) $q->where('is_active', $b ? 1 : 0);
            })
            ->when(!empty($data['from']), fn (Builder $q) => $q->whereDate('starts_on', '>=', $data['from']))
            ->when(!empty($data['to']),   fn (Builder $q) => $q->whereDate('ends_on',   '<=', $data['to']))
            ->when(!empty($data['q']), fn (Builder $q) => $q->where('year_label','like','%'.trim($data['q']).'%'));

        QueryFilters::applySorting($query, $data['sort'] ?? '-starts_on', [
            'id','year_label','starts_on','created_at'
        ]);

        $total = (clone $query)->count();
        if ($isAll) {
            $items = $query->get();
            $nextCursor = null;
        } else {
            $items = $query->skip($offset)->take($limit)->get();
            $nextOffset = $offset + $items->count();
            $nextCursor = ($nextOffset < $total) ? Cursor::encode(['offset'=>$nextOffset]) : null;
        }

        return ApiResponse::ok($items, ['count'=>$items->count(),'total'=>$total,'next_cursor'=>$nextCursor], ['next'=>$nextCursor]);
    }
}
