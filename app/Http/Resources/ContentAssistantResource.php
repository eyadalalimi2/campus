<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentAssistantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => (int) $this->id,
            'name'            => $this->name,
            'photo_url'       => $this->photo_url,
            'university_text' => $this->university_text,
            'college_text'    => $this->college_text,
            'major_text'      => $this->major_text,
            'sort_order'      => (int) $this->sort_order,
        ];
    }
}
