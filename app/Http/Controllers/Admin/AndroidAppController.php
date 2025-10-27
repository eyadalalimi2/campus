<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AndroidApp;
use Illuminate\Support\Facades\Storage;

class AndroidAppController extends Controller
{
    public function index()
    {
        $apps = AndroidApp::orderBy('created_at','desc')->paginate(15);
        return view('admin.android_apps.index', compact('apps'));
    }

    public function create()
    {
        return view('admin.android_apps.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'slug' => 'required|string|max:191|unique:android_apps,slug',
            'icon' => 'nullable|image|max:2048',
            'feature_image' => 'nullable|image|max:4096',
            'screenshots.*' => 'nullable|image|max:4096',
            'video_url' => 'nullable|string',
            'video_cover_image' => 'nullable|image|max:4096',
            'short_description' => 'nullable|string|max:200',
            'long_description' => 'nullable|string',
            'changelog' => 'nullable|string',
            'apk_file' => 'nullable|file|max:51200',
            'developer_logo' => 'nullable|image|max:4096',
            // tags handled via tags_text input (comma separated)
            'tags_text' => 'nullable|string'
        ]);

        $app = new AndroidApp();
        $app->name = $data['name'];
        $app->slug = $data['slug'];
        $app->short_description = $data['short_description'] ?? null;
        $app->long_description = $data['long_description'] ?? null;
        $app->changelog = $data['changelog'] ?? null;

        if ($request->hasFile('icon')) {
            $app->icon_path = $request->file('icon')->store('apps', 'public');
        }
        if ($request->hasFile('feature_image')) {
            $app->feature_image_path = $request->file('feature_image')->store('apps', 'public');
        }
        if ($request->hasFile('video_cover_image')) {
            $app->video_cover_image = $request->file('video_cover_image')->store('apps', 'public');
        }
        if ($request->hasFile('developer_logo')) {
            $app->developer_logo = $request->file('developer_logo')->store('apps', 'public');
        }
        if ($request->hasFile('apk_file')) {
            $app->apk_file_path = $request->file('apk_file')->store('apps', 'public');
        }

        // screenshots
        $shots = [];
        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $file) {
                $shots[] = $file->store('apps', 'public');
            }
        }
        $app->screenshots = $shots ?: null;

        $app->video_url = $data['video_url'] ?? null;
        $app->version_name = $request->input('version_name');
        $app->version_code = $request->input('version_code');
        $app->apk_size = $request->input('apk_size');
        $app->min_sdk = $request->input('min_sdk');
        $app->target_sdk = $request->input('target_sdk');
        $app->published_at = $request->input('published_at');
        $app->privacy_policy_url = $request->input('privacy_policy_url');
        $app->support_email = $request->input('support_email');
        $app->website_url = $request->input('website_url');
        $app->category = $request->input('category');
        $app->developer_name = $request->input('developer_name');
        // parse tags_text -> array
        $tagsText = $request->input('tags_text');
        if ($tagsText) {
            $tags = array_filter(array_map('trim', explode(',', $tagsText)));
            $app->tags = $tags ?: null;
        } else {
            $app->tags = null;
        }

        $app->save();

        return redirect()->route('admin.apps.index')->with('success', 'تم إنشاء التطبيق بنجاح');
    }

    public function edit(AndroidApp $app)
    {
        return view('admin.android_apps.edit', compact('app'));
    }

    public function update(Request $request, AndroidApp $app)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'slug' => 'required|string|max:191|unique:android_apps,slug,'.$app->id,
            'icon' => 'nullable|image|max:2048',
            'feature_image' => 'nullable|image|max:4096',
            'screenshots.*' => 'nullable|image|max:4096',
            'video_url' => 'nullable|string',
            'video_cover_image' => 'nullable|image|max:4096',
            'short_description' => 'nullable|string|max:200',
            'long_description' => 'nullable|string',
            'changelog' => 'nullable|string',
            'apk_file' => 'nullable|file|max:51200',
            'developer_logo' => 'nullable|image|max:4096',
            'tags_text' => 'nullable|string'
        ]);

        $app->name = $data['name'];
        $app->slug = $data['slug'];
        $app->short_description = $data['short_description'] ?? null;
        $app->long_description = $data['long_description'] ?? null;
        $app->changelog = $data['changelog'] ?? null;

        if ($request->hasFile('icon')) {
            $app->icon_path = $request->file('icon')->store('apps', 'public');
        }
        if ($request->hasFile('feature_image')) {
            $app->feature_image_path = $request->file('feature_image')->store('apps', 'public');
        }
        if ($request->hasFile('video_cover_image')) {
            $app->video_cover_image = $request->file('video_cover_image')->store('apps', 'public');
        }
        if ($request->hasFile('developer_logo')) {
            $app->developer_logo = $request->file('developer_logo')->store('apps', 'public');
        }
        if ($request->hasFile('apk_file')) {
            $app->apk_file_path = $request->file('apk_file')->store('apps', 'public');
        }

        // screenshots (append new ones)
        $shots = $app->screenshots ?? [];
        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $file) {
                $shots[] = $file->store('apps', 'public');
            }
        }
        $app->screenshots = $shots ?: null;

        $app->video_url = $data['video_url'] ?? null;
        $app->version_name = $request->input('version_name');
        $app->version_code = $request->input('version_code');
        $app->apk_size = $request->input('apk_size');
        $app->min_sdk = $request->input('min_sdk');
        $app->target_sdk = $request->input('target_sdk');
        $app->published_at = $request->input('published_at');
        $app->privacy_policy_url = $request->input('privacy_policy_url');
        $app->support_email = $request->input('support_email');
        $app->website_url = $request->input('website_url');
        $app->category = $request->input('category');
        $app->developer_name = $request->input('developer_name');
        $tagsText = $request->input('tags_text');
        if ($tagsText) {
            $tags = array_filter(array_map('trim', explode(',', $tagsText)));
            $app->tags = $tags ?: null;
        } else {
            $app->tags = $app->tags ?? null;
        }

        $app->save();

        return redirect()->route('admin.apps.index')->with('success', 'تم تحديث التطبيق');
    }

    public function destroy(AndroidApp $app)
    {
        // لا نحذف الملفات فعليًا هنا لتفادي فقدان غير مقصود، لكن يمكن حذفها إن أردت
        $app->delete();
        return redirect()->route('admin.apps.index')->with('success', 'تم حذف التطبيق');
    }
}
