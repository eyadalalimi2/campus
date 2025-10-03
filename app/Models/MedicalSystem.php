<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalSystem extends Model
{
    protected $table = 'MedicalSystems';
    protected $fillable = [
        'year_id', 'term_id', 'med_device_id', 'display_name', 'notes', 'is_active', 'sort_order',
    ];
    public function term()
    {
    return $this->belongsTo(\App\Models\MedicalTerm::class, 'term_id'); // جدول MedicalTerms
    }

    public function year()
    {
        return $this->belongsTo(MedicalYear::class, 'year_id');
    }

    // يعتمد على موديل الجهاز العام med_devices
    public function device()
    {
        return $this->belongsTo(\App\Models\MedDevice::class, 'med_device_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(MedicalSubject::class, 'MedicalSystemSubjects', 'system_id', 'subject_id');
    }
}