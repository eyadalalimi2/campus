<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedVideo extends Model
{
    use HasFactory;
    protected $table = 'med_videos';
    protected $fillable = [
        'doctor_id','subject_id','topic_id','title','thumbnail_url','youtube_url',
        'order_index','status','published_at'
    ];
    protected $casts = ['published_at' => 'datetime'];

    public function doctor(){ return $this->belongsTo(MedDoctor::class,'doctor_id'); }
    public function subject(){ return $this->belongsTo(MedSubject::class,'subject_id'); }
    public function topic(){ return $this->belongsTo(MedTopic::class,'topic_id'); }
}
