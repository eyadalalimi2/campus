<?php

namespace App\Http\Controllers\Api\V1\Academic;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Common\PaginateRequest;
use App\Support\ApiResponse;
use App\Support\Cursor;
use App\Support\QueryFilters;
use App\Models\AcademicTerm;
use App\Models\AcademicCalendar;
use Illuminate\Database\Eloquent\Builder;

final class TermsController extends Controller
{
    /**
     * GET /api/v1/calendars/{id}/terms
     * فلاتر: is_active, from, to, q (على name), sort, limit, cursor
     * sort المسموح: name, -name, starts_on, -starts_on, id, -id, created_at, -created_at
     */
    public function byCalendar(PaginateRequest $request, int $id)
    {
        if (!AcademicCalendar::query()->whereKey($id)->exists()) {
            return ApiResponse::error('NOT_FOUND','التقويم الأكاديمي غير موجود.',[],404);
        }

        $data   = $request->validated();
        $limit  = min((int)($data['limit'] ?? config('api.pagination.default', 20)), config('api.pagination.max', 50));
        $cursor = Cursor::decode($data['cursor'] ?? null);
        $offset = (int)($cursor['offset'] ?? 0);

        $query = AcademicTerm::query()
            ->select(['id','calendar_id','name','starts_on','ends_on','is_active','created_at'])
            ->where('calendar_id',$id)
            ->when(isset($data['is_active']), function (Builder $q) use ($data) {
                $b = filter_var($data['is_active'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($b !== null) $q->where('is_active', $b ? 1 : 0);
            })
            ->when(!empty($data['from']), fn (Builder $q) => $q->whereDate('starts_on','>=',$data['from']))
            ->when(!empty($data['to']),   fn (Builder $q) => $q->whereDate('ends_on','<=',$data['to']))
            ->when(!empty($data['q']),   fn (Builder $q) => $q->where('name','like','%'.trim($data['q']).'%'));

        QueryFilters::applySorting($query, $data['sort'] ?? '-starts_on', [
            'id','name','starts_on','created_at'
        ]);

        $total = (clone $query)->count();
        $items = $query->skip($offset)->take($limit)->get();

        $nextOffset = $offset + $items->count();
        $nextCursor = ($nextOffset < $total) ? Cursor::encode(['offset'=>$nextOffset]) : null;

        return ApiResponse::ok($items, ['count'=>$items->count(),'total'=>$total,'next_cursor'=>$nextCursor], ['next'=>$nextCursor]);
    }
}
