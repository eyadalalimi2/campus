<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PracticePdfResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'file_url' => $this->file ? asset('storage/' . $this->file) : null,
            'order' => $this->order,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
