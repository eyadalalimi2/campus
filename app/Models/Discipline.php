<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discipline extends Model
{
    use HasFactory;

    /**
     * الحقول التي يمكن تعبئتها جماعيًا.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'is_active',
    ];

    /**
     * تحويل الأنواع عند الإرجاع.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * البرامج التابعة لهذا المجال.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class);
    }

    /**
     * الأصول العامة (Assets) المرتبطة بهذا المجال.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }
}
