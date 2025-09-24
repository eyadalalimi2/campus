<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class QuestionStat extends Model
{
    protected $table = 'med_question_stats';
    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'attempts',
        'correct',
        'correct_rate',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'attempts'     => 'integer',
        'correct'      => 'integer',
        'correct_rate' => 'float',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
