<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Reviews\StoreReviewRequest;
use App\Http\Requests\Api\V1\Reviews\UpdateReviewRequest;
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

        // منع تكرار التقييم: مستخدم واحد ← تقييم واحد
        $existing = Review::where('user_id', $user->id)->first();
        if ($existing) {
            return response()->json([
                'code'    => 'REVIEW_EXISTS',
                'message' => 'لديك تقييم سابق. استخدم تعديل التقييم.',
                'data'    => $existing->load('replyAdmin'),
            ], 409);
        }

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

    // PUT/PATCH v1/me/reviews — تحديث التقييم الحالي للمستخدم
    public function update(UpdateReviewRequest $request)
    {
        $user = $request->user();
        $review = Review::where('user_id', $user->id)->first();

        if (!$review) {
            return response()->json([
                'code'    => 'REVIEW_NOT_FOUND',
                'message' => 'لا يوجد لديك تقييم سابق لإنشاء تعديل عليه.',
            ], 404);
        }

        $data = $request->validated();

        if (array_key_exists('comment', $data)) {
            $review->comment = $data['comment'];
        }
        if (array_key_exists('rating', $data) && $data['rating'] !== null) {
            $review->rating = (int) $data['rating'];
        }

        // إعادة الحالة إلى "pending" لإعادة المراجعة، ومسح رد الإدارة السابق إن وُجد
        $review->status = 'pending';
        $review->reply_text = null;
        $review->reply_admin_id = null;
        $review->replied_at = null;
        $review->save();

        return response()->json([
            'message' => 'تم تحديث التقييم، وسيتم مراجعته من الإدارة.',
            'data'    => $review->load('replyAdmin'),
        ]);
    }
}
