<?php
namespace App\Http\Resources\Medical;

use Illuminate\Http\Resources\Json\JsonResource;

class ResourceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => (int) $this->id,
            'type'  => $this->type,
            'track' => $this->track,
            'title' => ['ar'=>$this->title, 'en'=>$this->title_en],
            'desc'  => $this->description,
            'lang'  => $this->language,
            'country' => $this->country,
            'year'  => $this->year ? (int) $this->year : null,
            'level' => $this->level,
            'rating'=> (float) $this->rating,
            'popularity'=> (int) $this->popularity,
            'duration_min'=> $this->duration_min ? (int)$this->duration_min : null,
            'size_mb' => $this->size_mb ? (float)$this->size_mb : null,
            'cover' => $this->cover_url,
            'source'=> $this->source_url,
            'license'=> $this->license,
            'visibility'=> $this->visibility,
            'status'=> $this->status,
            'subject'=> $this->whenLoaded('subject', fn()=> new SubjectResource($this->subject)),
            'system' => $this->whenLoaded('system', fn()=> new SystemResource($this->system)),
            'doctor' => $this->whenLoaded('doctor', fn()=> new DoctorResource($this->doctor)),
            'files'  => $this->whenLoaded('files', function(){
                return $this->files->map(function($f){
                    return [
                        'id'=>(int)$f->id,'path'=>$f->storage_path,'cdn'=>$f->cdn_url,
                        'bytes'=>$f->bytes ? (int)$f->bytes : null,
                        'sha256'=>$f->hash_sha256,'download_allowed'=> (bool)$f->download_allowed
                    ];
                });
            }),
            'youtube'=> $this->whenLoaded('youtubeMeta', function(){
                return [
                    'provider' => $this->youtubeMeta->provider,
                    'channel_id' => $this->youtubeMeta->channel_id,
                    'video_id'   => $this->youtubeMeta->video_id,
                    'playlist_id'=> $this->youtubeMeta->playlist_id,
                    'stats'      => $this->youtubeMeta->external_stats,
                ];
            }),
        ];
    }
}
