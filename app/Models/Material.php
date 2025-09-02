<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name','scope','university_id','college_id','major_id','level','term','is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level'     => 'integer',
    ];

    public function university(){ return $this->belongsTo(University::class); }
    public function college(){ return $this->belongsTo(College::class); }
    public function major(){ return $this->belongsTo(Major::class); }

    public function devices(){ return $this->hasMany(Device::class); }
    public function assets(){ return $this->hasMany(Asset::class); }
}
