<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'email'           => $this->email,
            'phone'           => $this->phone,
            'student_number'  => $this->student_number,
            'country'         => $this->country,
            'email_verified'  => !is_null($this->email_verified_at),
            'avatar_url'      => $this->avatar_url ?? ($this->avatar_path ? (url('storage/'.$this->avatar_path)) : null),

            'university_id'   => $this->university_id,
            'college_id'      => $this->college_id,
            'major_id'        => $this->major_id,

            'created_at'      => optional($this->created_at)->toIso8601String(),
            'updated_at'      => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
