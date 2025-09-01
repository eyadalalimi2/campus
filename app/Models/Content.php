<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $fillable = [
        'title','description','type','source_url','file_path',
        'scope','university_id','college_id','major_id','material_id','doctor_id','is_active'
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function university(){ return $this->belongsTo(University::class); }
    public function college(){ return $this->belongsTo(College::class); }
    public function major(){ return $this->belongsTo(Major::class); }
    public function doctor(){ return $this->belongsTo(Doctor::class); }

    // إضافات
    public function material(){ return $this->belongsTo(Material::class); }
    public function devices(){  return $this->belongsToMany(Device::class, 'content_device'); }
    

    public function getFileUrlAttribute(){
        return $this->file_path ? asset('storage/'.$this->file_path) : null;
    }

    public function scopeGlobal($q){ return $q->where('scope','global'); }
    public function scopeForUniversity($q, $universityId){
        return $q->where('scope','university')->where('university_id',$universityId);
    }
}
