<?php

namespace App\Models;

use App\Models\MedSubject;
use App\Models\MedVideo;
use App\Models\Concerns\HasRandomSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedDoctor extends Model
{
    use HasFactory, HasRandomSlug;
    protected $table = 'med_doctors';
    protected $fillable = ['name','avatar_path','bio','order_index','status','slug'];

    public function subjects()
    {
        return $this->belongsToMany(MedSubject::class, 'med_doctor_subject', 'doctor_id', 'subject_id');
    }

    public function videos()
    {
        return $this->hasMany(MedVideo::class, 'doctor_id');
    }
}
