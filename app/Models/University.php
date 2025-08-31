<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $fillable = [
        'name','slug','code','logo_path','favicon_path','primary_color','secondary_color','is_active'
    ];

    public function colleges() { return $this->hasMany(College::class); }

    // مخرجات URL للصور
    public function getLogoUrlAttribute()     { return $this->logo_path ? asset('storage/'.$this->logo_path) : null; }
    public function getFaviconUrlAttribute()  { return $this->favicon_path ? asset('storage/'.$this->favicon_path) : null; }
}
