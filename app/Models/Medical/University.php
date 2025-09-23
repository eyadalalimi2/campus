<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class University extends Model {
    protected $table = 'med_universities';
    protected $fillable = ['name','country','is_active'];
}
