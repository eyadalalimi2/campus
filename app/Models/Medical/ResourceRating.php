<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class ResourceRating extends Model
{
    protected $table = 'med_resource_ratings';
    public $timestamps = false;

    protected $fillable = [
        'user_id','resource_id','rating','comment','created_at','updated_at'
    ];
}
