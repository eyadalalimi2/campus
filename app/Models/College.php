<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class College extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(UniversityBranch::class, 'branch_id');
    }

    public function university()
    {
        return $this->branch->university();
    }

    public function majors(): HasMany
    {
        return $this->hasMany(Major::class);
    }
}
