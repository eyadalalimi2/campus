<?php

namespace App\Models;

use App\Models\MedSubject;
use App\Models\MedVideo;
use App\Models\MedResource;
use App\Models\Concerns\HasRandomSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedTopic extends Model
{
    use HasFactory, HasRandomSlug;
    protected $table = 'med_topics';
    protected $fillable = ['subject_id','title','description','order_index','status','slug'];

    public function subject()
    {
        return $this->belongsTo(MedSubject::class, 'subject_id');
    }

    public function videos()
    {
        return $this->hasMany(MedVideo::class, 'topic_id');
    }

    public function resources()
    {
        return $this->hasMany(MedResource::class, 'topic_id');
    }
}
