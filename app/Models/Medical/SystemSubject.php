<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class SystemSubject extends Model
{
    protected $table = 'med_system_subjects';
    protected $fillable = ['system_id','subject_id','semester_hint','level'];

    public function system(){ return $this->belongsTo(System::class,'system_id'); }
    public function subject(){ return $this->belongsTo(Subject::class,'subject_id'); }
}
