<?php

namespace App\Models;

use App\Models\MedResource;
use Illuminate\Database\Eloquent\Model;

class MedResourceCategory extends Model
{
    protected $table = 'med_resource_categories';
    protected $fillable = ['name','code','order_index','active'];

    public function resources()
    {
        return $this->hasMany(MedResource::class, 'category_id');
    }
}
