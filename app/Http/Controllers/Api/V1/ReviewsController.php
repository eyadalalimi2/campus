<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Reviews\StoreReviewRequest;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewsController extends Controller
{
    // GET v1/reviews (public): أحدث/أفضل التقييمات الموافق عليها
    public function publicIndex(Request $request)
    {
        $sort = in_array($request->query('sort'), ['latest', 'top']) ? $request->query('sort') : 'latest';
        $limit = (int) max(1, min(50, (int) $request->query('limit', 10)));

        $q = Review::query()
            ->with(['replyAdmin'])
            ->where('status', 'approved');

        if ($sort === 'top') {
            $q->orderByDesc('rating')->orderByDesc('id');
        } else {
            $q->orderByDesc('id');
        }

        $items = $q->limit($limit)->get(['id','user_id','rating','comment','reply_text','reply_admin_id','replied_at','created_at']);

        // يمكن إخفاء أسماء المستخدمين إذا رغبت مستقبلاً
        return response()->json(['data' => $items]);
    }
    // GET v1/me/reviews
    public function index(Request $request)
    {
        $user = $request->user();
        $reviews = Review::with('replyAdmin')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => $reviews->items(),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page'    => $reviews->lastPage(),
                'per_page'     => $reviews->perPage(),
                'total'        => $reviews->total(),
            ],
        ]);
    }

    // POST v1/me/reviews
    public function store(StoreReviewRequest $request)
    {
        $user = $request->user();

        $review = Review::create([
            'user_id' => $user->id,
            'rating'  => (int) $request->integer('rating'),
            'comment' => $request->string('comment')->toString(),
            'status'  => 'pending', // افتراضيًا: انتظار المراجعة من الإدارة
        ]);

        return response()->json([
            'message' => 'تم إرسال التقييم بنجاح',
            'data'    => $review->load('replyAdmin'),
        ], 201);
    }
}
