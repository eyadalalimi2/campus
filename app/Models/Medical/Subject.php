<?php
namespace App\Models\Medical;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model {
    protected $table = 'med_subjects';
    protected $fillable = ['name_ar','name_en','track_scope','is_active'];
}
