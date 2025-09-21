<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniversityBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'name',
        'address',
        'phone',
        'email',
        'is_active',
    ];

    /** العلاقات */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function colleges()
    {
        return $this->hasMany(College::class, 'branch_id');
    }
}
