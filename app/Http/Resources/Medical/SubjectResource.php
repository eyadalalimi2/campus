<?php
namespace App\Http\Resources\Medical;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => (int) $this->id,
            'code'  => $this->code,
            'name'  => ['ar'=>$this->name_ar,'en'=>$this->name_en],
            'track' => $this->track_scope,
            'active'=> (bool) $this->is_active,
        ];
    }
}
