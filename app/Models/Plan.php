<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration_days',
        'is_active',
        'description',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'is_active'     => 'boolean',
    ];

    public function features(): HasMany
    {
        return $this->hasMany(PlanFeature::class);
    }



    public function scopeActive($q, $active = true)
    {
        return $q->where('is_active', $active ? 1 : 0);
    }
}
