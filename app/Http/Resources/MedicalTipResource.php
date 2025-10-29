<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalTipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'youtube_url' => $this->youtube_url,
            'order' => $this->order,
            'cover_url' => $this->cover ? asset('storage/' . $this->cover) : null,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
