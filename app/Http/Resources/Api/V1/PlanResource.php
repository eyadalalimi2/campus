<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

final class PlanResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id'            => (int) data_get($this->resource, 'id'),
            'code'          => data_get($this->resource, 'code'),
            'name'          => data_get($this->resource, 'name'),
            'price_cents'   => data_get($this->resource, 'price_cents') !== null ? (int) data_get($this->resource, 'price_cents') : null,
            'currency'      => data_get($this->resource, 'currency'),
            'billing_cycle' => data_get($this->resource, 'billing_cycle'),
            'is_active'     => (bool) data_get($this->resource, 'is_active', true),
            'created_at'    => data_get($this->resource, 'created_at') ? (string) data_get($this->resource, 'created_at') : null,
            'updated_at'    => data_get($this->resource, 'updated_at') ? (string) data_get($this->resource, 'updated_at') : null,
        ];
    }
}
