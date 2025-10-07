<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'dashboard_logo',
        'dashboard_favicon',
        'admin_login_logo',
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
    ];
}
