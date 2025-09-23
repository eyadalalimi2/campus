<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class DoctorSubjectSystem extends Model {
    protected $table = 'med_doctor_subject_systems';
    protected $fillable = ['doctor_subject_id','system_id','playlist_id','tag'];

    public function doctorSubject(){ return $this->belongsTo(DoctorSubject::class,'doctor_subject_id'); }
    public function system(){ return $this->belongsTo(System::class,'system_id'); }
}
