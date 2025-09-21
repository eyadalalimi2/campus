<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',        // university | independent
        'university_id',
        'branch_id',
        'college_id',
        'major_id',
        'degree',
        'degree_year',
        'phone',
        'photo_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'degree_year' => 'integer',
    ];
    public function branch()
    {
        return $this->belongsTo(UniversityBranch::class);
    }


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

    /**
     * التخصصات الإضافية للمدرس عبر جدول pivot doctor_major.
     */
    public function majors(): BelongsToMany
    {
        return $this->belongsToMany(Major::class, 'doctor_major');
    }

    /**
     * رابط الصورة للعرض.
     */
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }
}
