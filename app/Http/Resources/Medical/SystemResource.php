<?php
namespace App\Http\Resources\Medical;

use Illuminate\Http\Resources\Json\JsonResource;

class SystemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'    => (int) $this->id,
            'code'  => $this->code,
            'name'  => ['ar'=>$this->name_ar,'en'=>$this->name_en],
            'icon'  => $this->icon_url,
            'order' => (int) $this->display_order,
            'active'=> (bool) $this->is_active,
        ];
    }
}
