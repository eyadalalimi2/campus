<?php
namespace App\Http\Resources\Medical;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>(int)$this->id,
            'type'=>$this->type,
            'stem'=>$this->stem,
            'difficulty'=>$this->difficulty,
            'bloom'=>$this->bloom,
            'tags'=>$this->tags,
            'explanation'=>$this->explanation,
            'options'=>$this->whenLoaded('options', function(){
                return $this->options->map(fn($o)=>[
                    'id'=>(int)$o->id,'text'=>$o->option_text,'is_correct'=>(bool)$o->is_correct
                ]);
            }),
            'stats'=>$this->whenLoaded('stats', function(){
                return [
                    'attempts'=>(int)$this->stats->attempts,
                    'correct'=>(int)$this->stats->correct,
                    'correct_rate'=>(float)$this->stats->correct_rate,
                ];
            }),
        ];
    }
}
