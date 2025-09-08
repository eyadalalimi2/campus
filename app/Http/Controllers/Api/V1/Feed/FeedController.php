<?php

namespace App\Http\Controllers\Api\V1\Feed;

use App\Http\Controllers\Controller;
use App\Domain\Feed\BuildFeedService;
use App\Support\ApiResponse;
use App\Support\Cursor;
use App\Http\Resources\Api\V1\FeedItemResource;
use App\Exceptions\Api\ApiException;
use Illuminate\Support\Collection;

final class FeedController extends Controller
{
    public function __construct(private BuildFeedService $svc) {}

    /**
     * GET /api/v1/me/feed
     */
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            throw new ApiException('UNAUTHORIZED', 'الرجاء تسجيل الدخول.', 401);
        }

        $default = (int) config('api.pagination.default', 20);
        $max     = (int) config('api.pagination.max', 50);

        $limit  = (int) request()->integer('limit', $default);
        $limit  = max(1, min($limit, $max));
        $cursor = Cursor::decode(request()->get('cursor'));

        // خدمة البناء تُرجع [array $items, ?string $nextCursor]
        [$items, $nextCursor] = $this->svc->build([
            'user_id'       => $user->id,
            'university_id' => $user->university_id ?? null,
            'college_id'    => $user->college_id ?? null,
            'major_id'      => $user->major_id ?? null,
            'limit'         => $limit,
            'cursor'        => $cursor,
        ]);

        // تحويل إلى Collection من كائنات لتوافق الـ Resource
        /** @var Collection<int,object> $collection */
        $collection = collect($items)->map(fn (array $row) => (object) $row);

        $links = [];
        if ($nextCursor) {
            $links['next'] = route('api.v1.feed.index', ['cursor' => $nextCursor, 'limit' => $limit]);
        }

        return ApiResponse::ok(
            FeedItemResource::collection($collection),
            ['count' => $collection->count(), 'next_cursor' => $nextCursor],
            $links
        );
    }
}
