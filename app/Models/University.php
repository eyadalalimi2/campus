<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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
        'is_active'        => 'boolean',
        'use_default_theme'=> 'boolean',
    ];

    /**
     * الدولة التي تنتمي إليها الجامعة.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * الفروع التابعة للجامعة.
     */
    public function branches(): HasMany
    {
        return $this->hasMany(UniversityBranch::class);
    }

    /**
     * الكليات التابعة للجامعة عبر الفروع.
     */
    public function colleges(): HasManyThrough
    {
        return $this->hasManyThrough(
            College::class,
            UniversityBranch::class,
            'university_id', // Foreign key on branches
            'branch_id',     // Foreign key on colleges
            'id',            // Local key on universities
            'id'             // Local key on branches
        );
    }

    /**
     * التقويمات الأكاديمية الخاصة بالجامعة.
     */
    public function academicCalendars(): HasMany
    {
        return $this->hasMany(AcademicCalendar::class);
    }
}
