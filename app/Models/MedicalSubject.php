<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalSubject extends Model
{
    protected $table = 'MedicalSubjects';
    protected $fillable = [
        'term_id', 'med_subject_id', 'track', 'display_name', 'notes', 'is_active', 'sort_order', 'image',
    ];

    public function term()
    {
        return $this->belongsTo(MedicalTerm::class, 'term_id');
    }

    // يعتمد على وجود موديل med_* العام. لا نعدّله.
    public function medSubject()
    {
        return $this->belongsTo(\App\Models\MedSubject::class, 'med_subject_id');
    }

    public function systems()
    {
        return $this->belongsToMany(MedicalSystem::class, 'MedicalSystemSubjects', 'subject_id', 'system_id')
            ->withPivot([]); // لا أعمدة إضافية الآن
    }

    public function links()
    {
        return $this->hasMany(MedicalSubjectContent::class, 'subject_id');
    }

    // shortcut لاسترجاع الـ contents المرتبطة
    public function contents()
    {
        return $this->belongsToMany(Content::class, 'MedicalSubjectContent', 'subject_id', 'content_id')
            ->withPivot(['sort_order', 'is_primary', 'notes'])
            ->orderBy('MedicalSubjectContent.sort_order');
    }
}