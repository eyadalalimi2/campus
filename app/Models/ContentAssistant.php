<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContentAssistant extends Model
{
    use HasFactory;

    protected $table = 'content_assistants';

    protected $fillable = [
        'name',
        'photo_path',
        'university_text',
        'college_text',
        'major_text',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = ['photo_url'];

    public function scopeActive($q)
    {
        return $q->where('is_active', 1);
    }

    public function scopeOrderDefault($q)
    {
        return $q->orderBy('sort_order')->orderBy('id');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? asset('storage/'.$this->photo_path) : null;
    }
}
