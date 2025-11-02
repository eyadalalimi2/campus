<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalSubjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => (int) $this->id,
            'term_id'       => (int) $this->term_id,
            'med_subject_id'=> (int) $this->med_subject_id,
            'track'         => (string) $this->track,
            'name'          => (string) ($this->display_name ?: $this->base_name),
            'display_name'  => $this->display_name,
            'image'         => $this->image ? asset('storage/'.$this->image) : ($this->base_image ? asset('storage/'.$this->base_image) : null),
            'base' => [
                'name'       => $this->base_name ?? null,
                'slug'       => $this->base_slug ?? null,
                'image'      => $this->base_image ? asset('storage/'.$this->base_image) : null,
                'scope'      => $this->base_scope ?? null,
            ],
            'is_active'     => (bool) $this->is_active,
            'sort_order'    => (int) ($this->sort_order ?? 0),
        ];
    }
}