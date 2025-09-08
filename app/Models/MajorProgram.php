<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MajorProgram extends Model
{
    protected $table = 'major_program';
    public $timestamps = false;

    protected $fillable = [
        'major_id',
        'program_id',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
