<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'scope',         // global | university
        'university_id',
        'college_id',
        'major_id',
        'level',
        'term',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
    ];

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    /**
     * الفصول الأكاديمية المرتبطة بالمادة عبر جدول material_term.
     */
    public function terms(): BelongsToMany
    {
        return $this->belongsToMany(AcademicTerm::class, 'material_term');
    }
}
