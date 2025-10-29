<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchPdf extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'abstract',
        'file',
        'order',
        // 'authors' and 'degree_type' were removed per request
    ];
}
