<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

final class SubscriptionResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [
            'id'                  => (int) data_get($this->resource, 'id'),
            'user_id'             => (int) data_get($this->resource, 'user_id'),
            'activation_code_id'  => data_get($this->resource, 'activation_code_id') !== null ? (int) data_get($this->resource, 'activation_code_id') : null,
            'plan_id'             => (int) data_get($this->resource, 'plan_id'),
            'status'              => data_get($this->resource, 'status'),
            'started_at'          => data_get($this->resource, 'started_at') ? (string) data_get($this->resource, 'started_at') : null,
            'ends_at'             => data_get($this->resource, 'ends_at') ? (string) data_get($this->resource, 'ends_at') : null,
            'auto_renew'          => (bool) data_get($this->resource, 'auto_renew', false),
            'price_cents'         => data_get($this->resource, 'price_cents') !== null ? (int) data_get($this->resource, 'price_cents') : null,
            'currency'            => data_get($this->resource, 'currency'),
            'created_at'          => data_get($this->resource, 'created_at') ? (string) data_get($this->resource, 'created_at') : null,
            'updated_at'          => data_get($this->resource, 'updated_at') ? (string) data_get($this->resource, 'updated_at') : null,
        ];
    }
}
