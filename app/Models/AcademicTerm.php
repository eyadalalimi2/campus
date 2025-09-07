<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AcademicTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'calendar_id',
        'name',
        'starts_on',
        'ends_on',
        'is_active',
    ];

    protected $casts = [
        'starts_on' => 'date',
        'ends_on' => 'date',
        'is_active' => 'boolean',
    ];

    public function calendar(): BelongsTo
    {
        return $this->belongsTo(AcademicCalendar::class, 'calendar_id');
    }

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'material_term', 'term_id', 'material_id');
    }
}
