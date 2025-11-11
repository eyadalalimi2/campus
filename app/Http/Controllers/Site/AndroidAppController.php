<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AndroidApp;
use Illuminate\Support\Facades\Storage;

class AndroidAppController extends Controller
{
    public function show($slug)
    {
        $app = AndroidApp::where('slug', $slug)->firstOrFail();
        // آخر التعليقات الموافق عليها (على مستوى المنصّة حالياً)
        // TODO: عند دعم ربط التقييمات بتطبيق معيّن أضِف شرط android_app_id
        $reviews = \App\Models\Review::with(['user:id,name,profile_photo_path','replyAdmin:id,name'])
            ->where('status','approved')
            ->latest()
            ->limit(15)
            ->get(['id','user_id','rating','comment','reply_text','reply_admin_id','replied_at','created_at']);

        // إحصاءات التقييمات العامة (متوسط، عدد، توزيع النجوم)
        $reviewsQuery = \App\Models\Review::query()->where('status','approved');
        $reviewsCount = (int) $reviewsQuery->count();
        $ratingAvg = (float) \App\Models\Review::where('status','approved')->avg('rating');
        $countsByStar = \App\Models\Review::where('status','approved')
            ->selectRaw('rating, COUNT(*) as c')
            ->groupBy('rating')
            ->pluck('c','rating');
        $breakdown = [];
        for ($i=1; $i<=5; $i++) {
            $cnt = (int) ($countsByStar[$i] ?? 0);
            $breakdown[$i] = $reviewsCount > 0 ? round($cnt * 100 / $reviewsCount) : 0;
        }
        return view('site.apps.show', [
            'app' => $app,
            'reviews' => $reviews,
            'rating' => $ratingAvg,
            'reviewsCount' => $reviewsCount,
            'breakdown' => $breakdown,
        ]);
    }

    public function download(Request $request, $slug)
    {
        $app = AndroidApp::where('slug', $slug)->firstOrFail();

        // If a specific release_id is provided, try to download that release's APK
        $releaseId = $request->query('release_id');
        if ($releaseId) {
            $release = $app->releases()->where('id', $releaseId)->first();
            if ($release && $release->apk_file_path && Storage::disk('public')->exists($release->apk_file_path)) {
                // increment app-level download counter
                $app->increment('downloads_total');
                // Serve with a proper .apk filename and Android MIME type so clients don't treat it as a zip
                $downloadName = $app->slug . '-v' . ($release->version_name ?? $release->id) . '.apk';
                return Storage::disk('public')->download($release->apk_file_path, $downloadName, [
                    'Content-Type' => 'application/vnd.android.package-archive'
                ]);
            }
            // if specific release not found or file missing, fall back to app default
        }

        if (!$app->apk_file_path || !Storage::disk('public')->exists($app->apk_file_path)) {
            abort(404);
        }

        // Increase app downloads and serve the APK (latest by default)
        $app->increment('downloads_total');
        $downloadName = $app->slug . '-v' . ($app->version_name ?? $app->id) . '.apk';
        return Storage::disk('public')->download($app->apk_file_path, $downloadName, [
            'Content-Type' => 'application/vnd.android.package-archive'
        ]);
    }
}
