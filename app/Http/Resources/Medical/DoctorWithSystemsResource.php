<?php
namespace App\Http\Resources\Medical;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorWithSystemsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'doctor' => new DoctorResource($this->doctor),
            'subject'=> new SubjectResource($this->subject),
            'systems'=> $this->whenLoaded('systems', function(){
                return $this->systems->map(function($s){
                    return [
                        'id' => (int) $s->id,
                        'code' => $s->code,
                        'name'=> ['ar'=>$s->name_ar,'en'=>$s->name_en],
                        'playlist_id' => $s->pivot->playlist_id ?? null,
                        'tag'         => $s->pivot->tag ?? null,
                    ];
                });
            }),
            'priority' => (int) $this->priority,
            'featured' => (bool) $this->featured,
        ];
    }
}
