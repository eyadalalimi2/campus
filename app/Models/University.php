<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class University extends Model
{
    protected $fillable = [
        'name','address','phone','logo_path','is_active',
        'primary_color','secondary_color','theme_mode',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function colleges(){ return $this->hasMany(College::class); }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? Storage::url($this->logo_path) : asset('images/logo.png');
    }

    public function getThemeAttribute(): array
    {
        return [
            'primary'   => $this->primary_color ?: '#0d6efd',
            'secondary' => $this->secondary_color ?: '#6c757d',
            'mode'      => $this->theme_mode ?: 'auto',
            'logo'      => $this->logo_url,
        ];
    }
}
