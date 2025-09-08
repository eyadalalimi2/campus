<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

final class UserResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id'                       => (int) data_get($this->resource, 'id'),
            'student_number'           => data_get($this->resource, 'student_number'),
            'name'                     => data_get($this->resource, 'name'),
            'email'                    => data_get($this->resource, 'email'),
            'phone'                    => data_get($this->resource, 'phone'),
            'country_id'               => data_get($this->resource, 'country_id') !== null ? (int) data_get($this->resource, 'country_id') : null,
            'profile_photo_path'       => data_get($this->resource, 'profile_photo_path'),
            'university_id'            => data_get($this->resource, 'university_id') !== null ? (int) data_get($this->resource, 'university_id') : null,
            'college_id'               => data_get($this->resource, 'college_id') !== null ? (int) data_get($this->resource, 'college_id') : null,
            'major_id'                 => data_get($this->resource, 'major_id') !== null ? (int) data_get($this->resource, 'major_id') : null,
            'level'                    => data_get($this->resource, 'level') !== null ? (int) data_get($this->resource, 'level') : null,
            'gender'                   => data_get($this->resource, 'gender'),
            'status'                   => data_get($this->resource, 'status'),
            'email_verified_at'        => data_get($this->resource, 'email_verified_at') ? (string) data_get($this->resource, 'email_verified_at') : null,
            'has_active_subscription'  => (bool) data_get($this->resource, 'has_active_subscription', false),
            'created_at'               => data_get($this->resource, 'created_at') ? (string) data_get($this->resource, 'created_at') : null,
            'updated_at'               => data_get($this->resource, 'updated_at') ? (string) data_get($this->resource, 'updated_at') : null,
        ];
    }
}
