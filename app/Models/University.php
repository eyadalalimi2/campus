<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class University extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'logo_path',
        'is_active', // ✅ أضفنا الحقل هنا
    ];

    protected $casts = [
        'is_active' => 'boolean', // ✅ يجعل القيمة Boolean تلقائيًا
    ];

    public function colleges()
    {
        return $this->hasMany(College::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path
            ? Storage::url($this->logo_path)
            : asset('images/logo.png');
    }
}
