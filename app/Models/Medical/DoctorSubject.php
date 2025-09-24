<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class DoctorSubject extends Model {
    public function systems(){
        return $this->belongsToMany(System::class,'med_doctor_subject_systems','doctor_subject_id','system_id')
            ->withPivot(['playlist_id','tag']);
    }
    protected $table = 'med_doctor_subjects';
    protected $fillable = ['doctor_id','subject_id','priority','featured'];

    public function doctor(){ return $this->belongsTo(Doctor::class,'doctor_id'); }
    public function subject(){ return $this->belongsTo(Subject::class,'subject_id'); }
}
