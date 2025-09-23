<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class System extends Model {
    protected $table = 'med_systems';
    protected $fillable = ['name_ar','name_en','icon_url','display_order','is_active'];
}
