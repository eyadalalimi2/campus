<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PracticePdf extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'file',
        'order',
    ];
}
