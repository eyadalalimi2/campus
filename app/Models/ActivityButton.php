<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityButton extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'order',
    ];

    public function videos()
    {
        return $this->hasMany(ActivityVideo::class)->orderBy('order');
    }
}
