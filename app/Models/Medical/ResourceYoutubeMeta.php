<?php
namespace App\Models\Medical; use Illuminate\Database\Eloquent\Model;
class ResourceYoutubeMeta extends Model {
    protected $table='med_resource_youtube_meta';
    protected $fillable=['resource_id','provider','channel_id','video_id','playlist_id','external_stats'];
    protected $casts=['external_stats'=>'array'];
    public function resource(){ return $this->belongsTo(Resource::class,'resource_id'); }
}
