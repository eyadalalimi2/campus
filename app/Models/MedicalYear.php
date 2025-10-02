<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalYear extends Model
{
    protected $table = 'MedicalYears';
    protected $fillable = [
        'major_id', 'year_number', 'is_active', 'sort_order',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function terms()
    {
        return $this->hasMany(MedicalTerm::class, 'year_id');
    }

    public function systems()
    {
        return $this->hasMany(MedicalSystem::class, 'year_id');
    }
}