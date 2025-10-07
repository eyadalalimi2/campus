<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit()
    {
        $setting = Setting::first();
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::firstOrNew([]);
        $data = $request->only([
            'site_title',
            'site_short_description',
            'site_long_description',
            'seo_keywords',
            'seo_description',
            'contact_email',
            'contact_phone',
            'facebook_url',
            'twitter_url',
            'instagram_url',
        ]);

        // Handle file uploads
        foreach ([
            'dashboard_logo',
            'dashboard_favicon',
            'admin_login_logo'
        ] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $path = $file->store('settings', 'public');
                $data[$field] = $path;
            }
        }

        $setting->fill($data)->save();
        return redirect()->back()->with('success', 'تم تحديث الإعدادات بنجاح');
    }
}
