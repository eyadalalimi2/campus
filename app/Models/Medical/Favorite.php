<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'med_favorites';
    public $timestamps = false;

    protected $fillable = [
        'user_id','resource_id','created_at','updated_at'
    ];
}
