<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => (int)$this->id,
            'title'     => $this->title,
            'body'      => $this->body,
            'status'    => $this->status ?? 'unread',
            'read_at'   => $this->read_at,
            'created_at'=> $this->created_at,
        ];
    }
}
