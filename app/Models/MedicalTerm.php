<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MedicalTerm extends Model
{
    protected $table = 'MedicalTerms';
    protected $fillable = [
        'year_id', 'term_number', 'is_active', 'sort_order', 'image_path',
    ];

    public function year()
    {
        return $this->belongsTo(MedicalYear::class, 'year_id');
    }

    public function subjects()
    {
        return $this->hasMany(MedicalSubject::class, 'term_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }
}