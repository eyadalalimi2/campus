<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        // data قد تكون مصفوفة (cast) أو نص JSON
        $data = is_array($this->data) ? $this->data : ( $this->data ? json_decode($this->data, true) : [] );
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'body'       => $this->body,
            'type'       => $this->type,
            'target'     => [
                'type' => $this->target_type,
                'id'   => $this->target_id,
            ],
            'content_id' => $this->content_id,
            'asset_id'   => $this->asset_id,
            'action_url' => $data['action_url'] ?? null,
            'image_url'  => $data['image_url']  ?? null,
            'read_at'    => optional($this->read_at)->toISOString(),
            'created_at' => optional($this->created_at)->toISOString(),
        ];
    }
}
