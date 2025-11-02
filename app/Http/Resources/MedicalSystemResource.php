<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MedicalSystemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => (int) $this->id,
            'year_id'      => (int) $this->year_id,
            'med_device_id'=> (int) $this->med_device_id,
            'name'         => (string) ($this->display_name ?: $this->device_name),
            'display_name' => $this->display_name,
                'image'        => $this->image ? asset('storage/'.$this->image) : ($this->device_image ? asset('storage/'.$this->device_image) : null),
            'device' => [
                'name'  => $this->device_name ?? null,
                'slug'  => $this->device_slug ?? null,
                'image' => $this->device_image ? asset('storage/'.$this->device_image) : null,
            ],
            'is_active'   => (bool) $this->is_active,
            'sort_order'  => (int) ($this->sort_order ?? 0),
        ];
    }
}