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
        return view('site.apps.show', compact('app'));
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
