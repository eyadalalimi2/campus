<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UniversityBranch extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'university_id',
        'name',
        'address',
        'phone',
        'email',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*=============================
     | علاقات
     |=============================*/
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function colleges(): HasMany
    {
        return $this->hasMany(College::class, 'branch_id');
    }

    // علاقات مساندة (مفيدة للفلاتر والتقارير)
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'branch_id');
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class, 'branch_id');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class, 'branch_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class, 'branch_id');
    }

    /*=============================
     | Scopes (فلترة شائعة)
     |=============================*/
    public function scopeActive($q, bool $active = true)
    {
        return $q->where('is_active', $active);
    }

    public function scopeForUniversity($q, ?int $universityId)
    {
        return $universityId ? $q->where('university_id', $universityId) : $q;
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        return $q->where(function ($w) use ($term) {
            $w->where('name', 'like', "%{$term}%")
              ->orWhere('address', 'like', "%{$term}%")
              ->orWhere('phone', 'like', "%{$term}%")
              ->orWhere('email', 'like', "%{$term}%");
        });
    }
}
