<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicMajor extends Model
{
    protected $table = 'public_majors';

    protected $fillable = [
        'public_college_id', 'name', 'slug', 'status',
    ];

    public function publicCollege(): BelongsTo
    {
        return $this->belongsTo(PublicCollege::class, 'public_college_id');
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }
}
