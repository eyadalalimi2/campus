<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppContent extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'link_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . ltrim($this->image_path, '/')) : null;
    }

    /* Scopes */
    public function scopeActive($q)  { return $q->where('is_active', 1); }
    public function scopeOrdered($q) { return $q->orderBy('sort_order')->orderBy('id'); }
}
