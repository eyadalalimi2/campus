<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'year_label',
        'starts_on',
        'ends_on',
        'is_active',
    ];

    protected $casts = [
        'starts_on' => 'date',
        'ends_on' => 'date',
        'is_active' => 'boolean',
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function terms(): HasMany
    {
        return $this->hasMany(AcademicTerm::class, 'calendar_id');
    }
    public function scopeForUniversity($q, ?int $universityId)
    {
        return $universityId ? $q->where('university_id', $universityId) : $q;
    }
}
