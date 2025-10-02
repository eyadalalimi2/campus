<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalSystemSubject extends Model
{
    protected $table = 'MedicalSystemSubjects';
    public $timestamps = false;
    protected $fillable = ['system_id', 'subject_id'];

    public function system()
    {
        return $this->belongsTo(MedicalSystem::class, 'system_id');
    }

    public function subject()
    {
        return $this->belongsTo(MedicalSubject::class, 'subject_id');
    }
}