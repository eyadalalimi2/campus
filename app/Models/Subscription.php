<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'user_id','plan','status','started_at','ends_at','auto_renew','price_cents','currency'
    ];
    protected $casts = [
        'started_at' => 'datetime',
        'ends_at' => 'datetime',
        'auto_renew' => 'boolean',
    ];

    public function user(){ return $this->belongsTo(User::class); }
}
