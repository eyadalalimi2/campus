<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AssetPublicMajor extends Pivot
{
    protected $table = 'asset_public_major';

    public $timestamps = false;

    protected $fillable = [
        'asset_id', 'public_major_id', 'is_primary', 'priority',
    ];
}
