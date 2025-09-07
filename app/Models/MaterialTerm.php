<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MaterialTerm extends Pivot
{
    protected $table = 'material_term';
    public $timestamps = false;
    protected $fillable = [
        'material_id',
        'term_id',
    ];
}
