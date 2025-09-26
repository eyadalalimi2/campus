<?php

namespace App\Models;

use App\Models\MedSubject;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasRandomSlug;
class MedDevice extends Model
{
      use HasRandomSlug;

    protected $table = 'med_devices';
    protected $fillable = ['name','image_path','order_index','status','slug'];

    public function subjects()
    {
        return $this->belongsToMany(MedSubject::class, 'med_device_subject', 'device_id', 'subject_id');
    }
}
