<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ComplaintResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'type'     => $this->type,          // content|asset|user|bug|abuse|other
            'subject'  => $this->subject,
            'body'     => $this->body,
            'severity' => $this->severity,      // low|medium|high|critical
            'status'   => $this->status,        // open|triaged|in_progress|resolved|rejected|closed
            'target'   => [
                'type' => $this->target_type,
                'id'   => $this->target_id,
            ],
            'attachment_url' => $this->attachment_path
                ? (str_starts_with($this->attachment_path, 'http')
                    ? $this->attachment_path
                    : Storage::url($this->attachment_path))
                : null,
            'closed_at'  => optional($this->closed_at)->toISOString(),
            'created_at' => optional($this->created_at)->toISOString(),
            'updated_at' => optional($this->updated_at)->toISOString(),
        ];
    }
}
