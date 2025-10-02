<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalSubjectContent extends Model
{
    protected $table = 'MedicalSubjectContent';
    protected $fillable = ['subject_id', 'content_id', 'sort_order', 'is_primary', 'notes'];

    public function subject()
    {
        return $this->belongsTo(MedicalSubject::class, 'subject_id');
    }

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }
}