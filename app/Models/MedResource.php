<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedResource extends Model
{
    use HasFactory;
    protected $table = 'med_resources';
    protected $fillable = [
        'subject_id','topic_id','category_id','title','description','file_url',
        'file_size_bytes','pages_count','order_index','status'
    ];

    public function subject(){ return $this->belongsTo(MedSubject::class,'subject_id'); }
    public function topic(){ return $this->belongsTo(MedTopic::class,'topic_id'); }
    public function category(){ return $this->belongsTo(MedResourceCategory::class,'category_id'); }
}
