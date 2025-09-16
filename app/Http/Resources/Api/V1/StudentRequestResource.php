<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => (int)$this->id,
            'type'      => $this->type,
            'subject'   => $this->subject,
            'body'      => $this->body,
            'status'    => $this->status,
            'admin_notes' => $this->admin_notes ?? null,
            'created_at'=> $this->created_at,
            'updated_at'=> $this->updated_at,
        ];
    }
}
