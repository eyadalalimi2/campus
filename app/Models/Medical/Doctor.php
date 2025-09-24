<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model {
    protected $table = 'med_doctors';
    protected $fillable = ['name','channel_url','country','verified','score','image'];
}
