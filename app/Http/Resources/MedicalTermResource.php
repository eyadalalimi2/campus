<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalTermResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => (int) $this->id,
            'year_id'     => (int) $this->year_id,
            'term_number' => (int) $this->term_number,
            'is_active'   => (bool) $this->is_active,
            'sort_order'  => (int) ($this->sort_order ?? 0),
        ];
    }
}