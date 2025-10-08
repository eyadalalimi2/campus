<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class AppFeatureResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => (int) $this->id,
            'text'       => $this->text,
            'image_url'  => $this->image_url,   // مُشتق من الموديل
            'sort_order' => (int) $this->sort_order,
        ];
    }
}
