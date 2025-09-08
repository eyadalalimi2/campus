<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

final class AssetResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id'            => (int) data_get($this->resource, 'id'),
            'category'      => data_get($this->resource, 'category'),
            'title'         => data_get($this->resource, 'title'),
            'description'   => data_get($this->resource, 'description'),
            'status'        => data_get($this->resource, 'status'),      // عند الفهرسة نستخدم published فقط
            'published_at'  => data_get($this->resource, 'published_at') ? (string) data_get($this->resource, 'published_at') : null,
            'material_id'   => data_get($this->resource, 'material_id') !== null ? (int) data_get($this->resource, 'material_id') : null,
            'discipline_id' => data_get($this->resource, 'discipline_id') !== null ? (int) data_get($this->resource, 'discipline_id') : null,
            'program_id'    => data_get($this->resource, 'program_id') !== null ? (int) data_get($this->resource, 'program_id') : null,
            'doctor_id'     => data_get($this->resource, 'doctor_id') !== null ? (int) data_get($this->resource, 'doctor_id') : null,

            // وسائط/روابط
            'media' => [
                'video_url'   => data_get($this->resource, 'video_url'),
                'file_path'   => data_get($this->resource, 'file_path'),
                'external_url'=> data_get($this->resource, 'external_url'),
            ],

            'created_at' => data_get($this->resource, 'created_at') ? (string) data_get($this->resource, 'created_at') : null,
            'updated_at' => data_get($this->resource, 'updated_at') ? (string) data_get($this->resource, 'updated_at') : null,
        ];
    }
}
