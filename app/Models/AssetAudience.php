<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetAudience extends Model
{
    protected $table = 'asset_audiences';
    public $timestamps = false;

    protected $fillable = [
        'asset_id',
        'major_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function major()
    {
        return $this->belongsTo(Major::class);
    }
}
