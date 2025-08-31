<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = ['college_id','name','code','is_active'];

    public function college() { return $this->belongsTo(College::class); }
}
