<?php

namespace App\Models;

use App\Models\MedDevice;
use App\Models\MedTopic;
use App\Models\MedDoctor;
use App\Models\MedVideo;
use App\Models\MedResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedSubject extends Model
{
    use HasFactory;
    protected $table = 'med_subjects';
    protected $fillable = ['name','image_path','scope','academic_level','order_index','status','slug'];

    public function devices()
    {
        return $this->belongsToMany(MedDevice::class, 'med_device_subject', 'subject_id', 'device_id');
    }

    public function topics()
    {
        return $this->hasMany(MedTopic::class, 'subject_id');
    }

    public function doctors()
    {
        return $this->belongsToMany(MedDoctor::class, 'med_doctor_subject', 'subject_id', 'doctor_id');
    }

    public function videos()
    {
        return $this->hasMany(MedVideo::class, 'subject_id');
    }

    public function resources()
    {
        return $this->hasMany(MedResource::class, 'subject_id');
    }
}
