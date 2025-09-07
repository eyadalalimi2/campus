<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class University extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'country_id',
        'phone',
        'logo_path',
        'primary_color',
        'secondary_color',
        'theme_mode',
        'is_active',
        'use_default_theme',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'use_default_theme' => 'boolean',
    ];

    /**
     * الدولة التي تنتمي إليها الجامعة.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * الكليات التابعة للجامعة.
     */
    public function colleges(): HasMany
    {
        return $this->hasMany(College::class);
    }

    /**
     * التقويم الأكاديمي الخاص بالجامعة.
     */
    public function academicCalendars(): HasMany
    {
        return $this->hasMany(AcademicCalendar::class);
    }
}
