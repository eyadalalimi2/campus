<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    protected $fillable = ['university_id','name','is_active'];

    public function university() { return $this->belongsTo(University::class); }
    public function majors()     { return $this->hasMany(Major::class); }
}
