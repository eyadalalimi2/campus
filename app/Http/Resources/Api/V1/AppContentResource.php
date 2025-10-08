<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AppContentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => (int)$this->id,
            'title'      => $this->title,
            'description'=> $this->description,
            'image_url'  => $this->image_url, // محسوب من الموديل
            'link_url'   => $this->link_url,
            'sort_order' => (int)$this->sort_order,
            'is_active'  => (bool)$this->is_active,
        ];
    }
}
