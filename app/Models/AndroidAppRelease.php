<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AndroidAppRelease extends Model
{
    use HasFactory;

    protected $table = 'android_app_releases';

    protected $fillable = [
        'android_app_id','version_name','version_code','apk_file_path','apk_size','changelog','published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function app()
    {
        return $this->belongsTo(AndroidApp::class, 'android_app_id');
    }
}
