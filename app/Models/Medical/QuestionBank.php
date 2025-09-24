<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    protected $table = 'med_question_banks';
    public $timestamps = false;

    protected $fillable = [
        'resource_id','total_questions','coverage','created_at','updated_at'
    ];

    protected $casts = [
        'coverage' => 'array',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }
}
