<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityVideo extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_button_id',
        'title',
        'youtube_url',
        'cover_image',
        'short_description',
        'order',
    ];

    public function button()
    {
        return $this->belongsTo(ActivityButton::class, 'activity_button_id');
    }
}
