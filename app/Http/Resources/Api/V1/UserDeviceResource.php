<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDeviceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'device_uuid'   => $this->device_uuid,
            'device_name'   => $this->device_name,
            'device_model'  => $this->device_model,
            'ip_address'    => $this->ip_address,
            'user_agent'    => $this->user_agent,
            'last_login_at' => optional($this->last_login_at)->toIso8601String(),
        ];
    }
}
