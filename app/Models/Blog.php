<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body',
        'status',
        'published_at',
        'university_id',
        'doctor_id',
        'cover_image_path',
        'is_active',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'published_at' => 'datetime',
        'deleted_at'   => 'datetime',
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}
