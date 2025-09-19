<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PublicCollege extends Model
{
    protected $table = 'public_colleges';

    protected $fillable = [
        'name', 'slug', 'status',
    ];

    public function publicMajors(): HasMany
    {
        return $this->hasMany(PublicMajor::class, 'public_college_id');
    }

    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }
}
