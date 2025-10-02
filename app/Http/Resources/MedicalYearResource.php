<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalYearResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => (int) $this->id,
            'major_id'    => (int) $this->major_id,
            'year_number' => (int) $this->year_number,
            'is_active'   => (bool) $this->is_active,
            'sort_order'  => (int) ($this->sort_order ?? 0),
        ];
    }
}