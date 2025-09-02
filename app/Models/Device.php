<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'material_id',
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
}
