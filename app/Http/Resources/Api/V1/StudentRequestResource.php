<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StudentRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'category'  => $this->category,   // general|material|account|technical|other
            'title'     => $this->title,
            'body'      => $this->body,
            'admin_notes' => $this->when($request->user() && $request->user()->id === $this->user_id, $this->admin_notes),
            'priority'  => $this->priority,   // low|normal|high
            'status'    => $this->status,     // open|in_progress|resolved|rejected|closed

            'attachment_url' => $this->attachment_path
                ? (str_starts_with($this->attachment_path, 'http')
                    ? $this->attachment_path
                    : Storage::disk('public')->url($this->attachment_path))
                : null,

            'material_id'=> $this->material_id,
            'content_id' => $this->content_id,

            'closed_at'  => optional($this->closed_at)->toISOString(),
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}

