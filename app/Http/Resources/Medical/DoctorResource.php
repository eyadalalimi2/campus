<?php
namespace App\Http\Resources\Medical;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'       => (int) $this->id,
            'name'     => $this->name,
            'channel'  => $this->channel_url,
            'country'  => $this->country,
            'verified' => (bool) $this->verified,
            'score'    => (float) $this->score,
        ];
    }
}
