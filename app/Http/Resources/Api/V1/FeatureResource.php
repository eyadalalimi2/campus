<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

final class FeatureResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id'           => (int) data_get($this->resource, 'id'),
            'plan_id'      => (int) data_get($this->resource, 'plan_id'),
            'feature_key'  => data_get($this->resource, 'feature_key'),
            'feature_value'=> data_get($this->resource, 'feature_value'),
            'created_at'   => data_get($this->resource, 'created_at') ? (string) data_get($this->resource, 'created_at') : null,
            'updated_at'   => data_get($this->resource, 'updated_at') ? (string) data_get($this->resource, 'updated_at') : null,
        ];
    }
}
