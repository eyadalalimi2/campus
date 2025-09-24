<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $table = 'med_question_options';
    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
