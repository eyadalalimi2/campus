<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class UniversityBranchResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request)
    {
        return [
            'id'            => (int) $this->id,
            'name'          => $this->name,
            'university_id' => (int) $this->university_id,
            'created_at'    => $this->created_at ? (string) $this->created_at : null,
            'updated_at'    => $this->updated_at ? (string) $this->updated_at : null,
        ];
    }
}
