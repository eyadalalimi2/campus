<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title','slug','excerpt','body','status','published_at',
        'university_id','doctor_id','cover_image_path','is_active'
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function university(){ return $this->belongsTo(University::class); }
    public function doctor(){ return $this->belongsTo(Doctor::class); }

    // نطاقات مفيدة
    public function scopePublished($q){ return $q->where('status','published'); }
    public function scopeDraft($q){ return $q->where('status','draft'); }
    public function scopeArchived($q){ return $q->where('status','archived'); }
}
