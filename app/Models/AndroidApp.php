<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AndroidAppRelease;

class AndroidApp extends Model
{
    use HasFactory;

    protected $table = 'android_apps';

    protected $fillable = [
        'name', 'slug', 'icon_path', 'feature_image_path', 'screenshots', 'video_url', 'video_cover_image',
        'short_description', 'long_description', 'changelog', 'version_name', 'version_code', 'apk_size',
        'min_sdk', 'target_sdk', 'published_at', 'downloads_total', 'downloads_today', 'apk_file_path',
        'privacy_policy_url', 'support_email', 'website_url', 'category', 'developer_name', 'developer_logo', 'tags'
    ];

    protected $casts = [
        'screenshots' => 'array',
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    // Helper to get public url for apk download
    public function getDownloadUrlAttribute()
    {
        if ($this->apk_file_path) {
            return route('apps.download', $this->slug);
        }
        return null;
    }

    /**
     * Releases (updates) for this Android app.
     */
    public function releases()
    {
        return $this->hasMany(AndroidAppRelease::class, 'android_app_id');
    }
}
