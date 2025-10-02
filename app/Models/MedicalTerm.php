<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalTerm extends Model
{
    protected $table = 'MedicalTerms';
    protected $fillable = [
        'year_id', 'term_number', 'is_active', 'sort_order',
    ];

    public function year()
    {
        return $this->belongsTo(MedicalYear::class, 'year_id');
    }

    public function subjects()
    {
        return $this->hasMany(MedicalSubject::class, 'term_id');
    }
}