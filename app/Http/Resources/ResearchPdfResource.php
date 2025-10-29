<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResearchPdfResource extends JsonResource
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
            'abstract' => $this->abstract,
            // authors and degree_type removed
            'file_url' => $this->file ? asset('storage/' . $this->file) : null,
            'order' => $this->order,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
