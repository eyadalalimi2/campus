<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class DoctorSubject extends Model {
    protected $table = 'med_doctor_subjects';
    protected $fillable = ['doctor_id','subject_id','priority','featured'];

    public function doctor(){ return $this->belongsTo(Doctor::class,'doctor_id'); }
    public function subject(){ return $this->belongsTo(Subject::class,'subject_id'); }
}
