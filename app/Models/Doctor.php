<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'name','type','university_id','college_id','major_id',
        'degree','degree_year','phone','photo_path','is_active'
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'degree_year' => 'integer',
    ];

    // روابط الجامعي
    public function university() { return $this->belongsTo(University::class); }
    public function college()    { return $this->belongsTo(College::class); }
    public function major()      { return $this->belongsTo(Major::class); }

    // روابط المستقل: تخصصات متعددة
    public function majors() { return $this->belongsToMany(Major::class, 'doctor_major'); }

    // صورة
    public function getPhotoUrlAttribute() {
        return $this->photo_path ? asset('storage/'.$this->photo_path) : null;
    }
}
