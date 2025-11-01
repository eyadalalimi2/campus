<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $q = Review::query()->with(['user', 'replyAdmin'])->latest();

        if ($request->filled('rating')) {
            $q->where('rating', (int) $request->input('rating'));
        }
        if ($request->filled('status')) {
            $status = $request->input('status');
            if (in_array($status, ['approved','pending','rejected'], true)) {
                $q->where('status', $status);
            }
        }
        if ($request->input('reply_filter') === 'has') {
            $q->whereNotNull('reply_text');
        } elseif ($request->input('reply_filter') === 'none') {
            $q->whereNull('reply_text');
        }
        if ($s = $request->input('q')) {
            $q->where(function($w) use ($s) {
                $w->where('comment', 'like', "%".trim($s)."%")
                  ->orWhereHas('user', function($wu) use ($s) {
                      $wu->where('name','like',"%".trim($s)."%")
                         ->orWhere('email','like',"%".trim($s)."%");
                  });
            });
        }

        $reviews = $q->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'replyAdmin']);
        return view('admin.reviews.show', compact('review'));
    }

    public function reply(Request $request, Review $review)
    {
        $data = $request->validate([
            'reply_text' => ['required','string','max:5000'],
        ]);

    $review->reply_text = $data['reply_text'];
    $review->reply_admin_id = $request->user('admin')->id;
    $review->replied_at = Carbon::now();
    // اعتماد التقييم تلقائيًا عند وجود رد من الإدارة
    $review->status = 'approved';
    $review->save();

        // إشعار المستخدم بالرد (طريقة موحّدة عبر الخدمة)
        \App\Services\Notify::toUser(
            userId: $review->user_id,
            title: 'تم الرد على تقييمك',
            body: mb_substr($review->reply_text, 0, 180),
            type: 'review_reply',
            targetType: 'review',
            targetId: $review->id,
            data: [
                'review_id'  => $review->id,
                'rating'     => $review->rating,
                'comment'    => $review->comment,
                'reply_text' => $review->reply_text,
            ]
        );

        return back()->with('status', 'تم حفظ الرد بنجاح');
    }

    public function updateStatus(Request $request, Review $review)
    {
        $data = $request->validate([
            'status' => ['required','in:approved,pending,rejected'],
        ]);

        $review->status = $data['status'];
        $review->save();

        return back()->with('status', 'تم تحديث حالة التقييم إلى: ' . $data['status']);
    }
}
