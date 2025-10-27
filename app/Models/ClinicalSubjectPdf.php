<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicalSubjectPdf extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'content',
        'file',
        'order',
        'clinical_subject_id',
    ];

    public function clinicalSubject()
    {
        return $this->belongsTo(ClinicalSubject::class);
    }
}
