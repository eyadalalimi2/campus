<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',           // university | independent
        'university_id',
        'branch_id',
        'college_id',
        'major_id',
        'public_college_id',
        'public_major_id',
        'degree',
        'degree_year',
        'phone',
        'photo_path',
        'is_active',
    ];
    public function public_college(): BelongsTo
    {
        return $this->belongsTo(\App\Models\PublicCollege::class, 'public_college_id');
    }

    public function public_major(): BelongsTo
    {
        return $this->belongsTo(\App\Models\PublicMajor::class, 'public_major_id');
    }

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'degree_year' => 'integer',
    ];

    protected $appends = [
        'photo_url',
    ];

    /*=============================
     | علاقات
     |=============================*/
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(UniversityBranch::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    /** التخصصات الإضافية عبر pivot doctor_major (doctor_id, major_id) */
    public function majors(): BelongsToMany
    {
        return $this->belongsToMany(Major::class, 'doctor_major', 'doctor_id', 'major_id');
    }

    /** (اختياري) محتويات هذا الدكتور */
    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    /** (اختياري) أصول/مواد تعليمية أنشأها هذا الدكتور */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /*=============================
     | Mutators
     |=============================*/
    public function setPasswordAttribute($value): void
    {
        if (!$value) return;
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function setEmailAttribute($value): void
    {
        $this->attributes['email'] = $value ? mb_strtolower(trim($value)) : null;
    }

    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = $value ? trim($value) : null;
    }

    /*=============================
     | Accessors
     |=============================*/
    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }

    /*=============================
     | Scopes (فلترة جاهزة)
     |=============================*/
    public function scopeActive($q, bool $active = true)
    {
        return $q->where('is_active', $active);
    }

    public function scopeForUniversity($q, ?int $universityId)
    {
        return $universityId ? $q->where('university_id', $universityId) : $q;
    }

    public function scopeForBranch($q, ?int $branchId)
    {
        return $branchId ? $q->where('branch_id', $branchId) : $q;
    }

    public function scopeForCollege($q, ?int $collegeId)
    {
        return $collegeId ? $q->where('college_id', $collegeId) : $q;
    }

    public function scopeForMajor($q, ?int $majorId)
    {
        return $majorId ? $q->where('major_id', $majorId) : $q;
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        $term = trim($term);

        return $q->where(function ($w) use ($term) {
            $w->where('name', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    /**
     * فلترة موحّدة للاستخدام في الكنترولرز:
     * يدعم: q, university_id, branch_id, college_id, major_id, is_active
     */
    public function scopeFilter($q, array $f = [])
    {
        return $q
            ->when(isset($f['q']) && $f['q'] !== '', fn($qq) => $qq->search($f['q']))
            ->when(!empty($f['university_id']), fn($qq) => $qq->forUniversity((int) $f['university_id']))
            ->when(!empty($f['branch_id']),     fn($qq) => $qq->forBranch((int) $f['branch_id']))
            ->when(!empty($f['college_id']),    fn($qq) => $qq->forCollege((int) $f['college_id']))
            ->when(!empty($f['major_id']),      fn($qq) => $qq->forMajor((int) $f['major_id']))
            ->when(isset($f['is_active']),      fn($qq) => $qq->active((bool) $f['is_active']));
    }
}
