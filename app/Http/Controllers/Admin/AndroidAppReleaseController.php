<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AndroidApp;
use App\Models\AndroidAppRelease;
use Illuminate\Support\Facades\Storage;

class AndroidAppReleaseController extends Controller
{
    // List releases for an app
    public function index(AndroidApp $app)
    {
        $releases = AndroidAppRelease::where('android_app_id', $app->id)->orderBy('published_at','desc')->get();
        return view('admin.android_apps.releases.index', compact('app','releases'));
    }

    public function create(AndroidApp $app)
    {
        return view('admin.android_apps.releases.create', compact('app'));
    }

    public function store(Request $request, AndroidApp $app)
    {
        $data = $request->validate([
            'version_name' => 'required|string|max:191',
            'version_code' => 'required|integer',
            'apk_file' => 'required|file|max:51200',
            'apk_size' => 'nullable|string',
            'changelog' => 'nullable|string',
            'published_at' => 'nullable|date'
        ]);

        // store apk
        $apkPath = $request->file('apk_file')->store('apps', 'public');

        $release = AndroidAppRelease::create([
            'android_app_id' => $app->id,
            'version_name' => $data['version_name'],
            'version_code' => $data['version_code'],
            'apk_file_path' => $apkPath,
            'apk_size' => $data['apk_size'] ?? null,
            'changelog' => $data['changelog'] ?? null,
            'published_at' => $data['published_at'] ?? now(),
        ]);

        // update main app record to point to the latest release (so front page shows latest)
        $app->version_name = $release->version_name;
        $app->version_code = $release->version_code;
        $app->apk_file_path = $release->apk_file_path;
        $app->apk_size = $release->apk_size;
        $app->published_at = $release->published_at;
        $app->changelog = $release->changelog;
        $app->save();

        return redirect()->route('admin.apps.releases.index', $app)->with('success','تم إضافة الإصدار بنجاح');
    }

    public function destroy(AndroidAppRelease $release)
    {
        // for shallow routes the app parameter is not provided, resolve it from the release
        $app = $release->app;

        // لا نحذف ملف الـAPK تلقائياً لتفادي فقدان النسخ القديمة بدون تأكيد
        $release->delete();

        return redirect()->route('admin.apps.releases.index', $app)->with('success','تم حذف الإصدار');
    }
}
