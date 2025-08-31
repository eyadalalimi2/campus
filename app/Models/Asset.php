<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'material_id','device_id','doctor_id','category','title','description',
        'video_url','file_path','external_url','is_active'
    ];
    protected $casts = ['is_active'=>'boolean'];

    public function material(){ return $this->belongsTo(Material::class); }
    public function device(){ return $this->belongsTo(Device::class); }
    public function doctor(){ return $this->belongsTo(Doctor::class); }

    public function getFileUrlAttribute(){ return $this->file_path ? asset('storage/'.$this->file_path) : null; }
}
