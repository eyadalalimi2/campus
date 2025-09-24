<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model {
    protected $table = 'med_resources';
    protected $fillable = [
        'type','track','subject_id','system_id','doctor_id','title','title_en','description',
        'language','country','year','authors','level','rating','popularity','duration_min',
        'size_mb','cover_url','source_url','license','visibility','status','created_by'
    ];
    protected $casts = ['authors'=>'array'];

    public function subject(){ return $this->belongsTo(Subject::class,'subject_id'); }
    public function system(){ return $this->belongsTo(System::class,'system_id'); }
    public function doctor(){ return $this->belongsTo(Doctor::class,'doctor_id'); }

    public function files(){ return $this->hasMany(ResourceFile::class,'resource_id'); }
    public function universities(){ return $this->belongsToMany(University::class,'med_resource_universities','resource_id','university_id'); }
    public function youtubeMeta(){ return $this->hasOne(ResourceYoutubeMeta::class,'resource_id'); }
    public function reference(){ return $this->hasOne(ResourceReferenceMeta::class,'resource_id'); }
}
