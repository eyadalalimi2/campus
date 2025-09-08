<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

final class ContentResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id'            => (int) data_get($this->resource, 'id'),
            'title'         => data_get($this->resource, 'title'),
            'description'   => data_get($this->resource, 'description'),
            'type'          => data_get($this->resource, 'type'), // file|video|link
            'status'        => data_get($this->resource, 'status'), // عند الفهرسة نستخدم published فقط
            'published_at'  => data_get($this->resource, 'published_at') ? (string) data_get($this->resource, 'published_at') : null,

            'university_id' => data_get($this->resource, 'university_id') !== null ? (int) data_get($this->resource, 'university_id') : null,
            'college_id'    => data_get($this->resource, 'college_id') !== null ? (int) data_get($this->resource, 'college_id') : null,
            'major_id'      => data_get($this->resource, 'major_id') !== null ? (int) data_get($this->resource, 'major_id') : null,
            'material_id'   => data_get($this->resource, 'material_id') !== null ? (int) data_get($this->resource, 'material_id') : null,
            'doctor_id'     => data_get($this->resource, 'doctor_id') !== null ? (int) data_get($this->resource, 'doctor_id') : null,

            // وسائط
            'media' => [
                'source_url' => data_get($this->resource, 'source_url'),
                'file_path'  => data_get($this->resource, 'file_path'),
            ],

            'created_at' => data_get($this->resource, 'created_at') ? (string) data_get($this->resource, 'created_at') : null,
            'updated_at' => data_get($this->resource, 'updated_at') ? (string) data_get($this->resource, 'updated_at') : null,
        ];
    }
}
