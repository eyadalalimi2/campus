<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class StudyGuideResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'youtube_url' => $this->youtube_url,
            'created_at' => optional($this->created_at)->toIso8601String(),
        ];
    }
}
