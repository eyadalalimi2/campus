<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'med_questions';
    public $timestamps = false;

    protected $fillable = [
        'resource_id','subject_id','system_id','type','stem',
        'difficulty','bloom','tags','explanation','source_ref',
        'created_at','updated_at'
    ];

    protected $casts = [
        'tags' => 'array',
    ];

    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id');
    }

    public function stats()
    {
        return $this->hasOne(QuestionStat::class, 'question_id');
    }
}
