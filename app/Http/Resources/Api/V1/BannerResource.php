<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'image_url'   => asset('storage/'.$this->image_path),
            'image_alt'   => $this->image_alt,
            'target_url'  => $this->target_url,
            'open_external' => $this->open_external,
            'sort_order'  => $this->sort_order,
        ];
    }
}
