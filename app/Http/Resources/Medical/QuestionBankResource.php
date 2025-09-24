<?php
namespace App\Http\Resources\Medical;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionBankResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>(int)$this->id,
            'resource'=> new ResourceResource($this->whenLoaded('resource', $this->resource)),
            'total_questions'=>(int)$this->total_questions,
            'coverage'=>$this->coverage,
        ];
    }
}
