<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Major extends Model
{
    use HasFactory;

    protected $fillable = [
        'college_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }
}
