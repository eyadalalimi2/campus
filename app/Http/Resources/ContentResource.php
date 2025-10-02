<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContentResource extends JsonResource
{
    public function toArray($request)
    {
        $fileUrl = $this->file_path ? asset('storage/'.$this->file_path) : null;

        return [
            'id'           => (int) $this->id,
            'title'        => (string) $this->title,
            'description'  => $this->description,
            'type'         => (string) $this->type,         // file | link
            'source_url'   => $this->source_url,
            'file_url'     => $fileUrl,
            'version'      => (int) ($this->version ?? 1),
            'published_at' => $this->published_at ? (string) $this->published_at : null,
            'is_active'    => (bool) $this->is_active,
            'status'       => (string) $this->status,
            // معلومات الربط (اختيارية)
            'pivot' => [
                'sort_order' => isset($this->sort_order) ? (int) $this->sort_order : 0,
                'is_primary' => isset($this->is_primary) ? (bool) $this->is_primary : false,
            ],
        ];
    }
}