<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

final class MaterialResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id'            => (int) data_get($this->resource, 'id'),
            'name'          => data_get($this->resource, 'name'),
            'scope'         => data_get($this->resource, 'scope'),
            'university_id' => data_get($this->resource, 'university_id') !== null ? (int) data_get($this->resource, 'university_id') : null,
            'college_id'    => data_get($this->resource, 'college_id') !== null ? (int) data_get($this->resource, 'college_id') : null,
            'major_id'      => data_get($this->resource, 'major_id') !== null ? (int) data_get($this->resource, 'major_id') : null,
            'level'         => data_get($this->resource, 'level') !== null ? (int) data_get($this->resource, 'level') : null,
            'is_active'     => (bool) data_get($this->resource, 'is_active', true),
            'created_at'    => data_get($this->resource, 'created_at') ? (string) data_get($this->resource, 'created_at') : null,
            'updated_at'    => data_get($this->resource, 'updated_at') ? (string) data_get($this->resource, 'updated_at') : null,
        ];
    }
}
