<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request)
    {
        return [
            'id'         => (int) $this->id,
            'title'      => $this->title,
            'sort_order' => (int) $this->sort_order,
            'is_active'  => (bool) $this->is_active,
            'created_at' => $this->created_at ? (string) $this->created_at : null,
            'updated_at' => $this->updated_at ? (string) $this->updated_at : null,
        ];
    }
}
