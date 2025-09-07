<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    /**
     * الأعمدة القابلة للتعبئة.
     */
    protected $fillable = [
        'name_ar',
        'iso2',
        'phone_code',
        'currency_code',
        'is_active',
    ];

    /**
     * التحويلات النوعية.
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * الجامعات التابعة للدولة.
     */
    public function universities(): HasMany
    {
        return $this->hasMany(University::class);
    }

    /**
     * المستخدمون (الطلاب) المنتمون لهذه الدولة.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
