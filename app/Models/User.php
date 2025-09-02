<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // إن كان مشروعك يستخدم Auth الافتراضي
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'student_number',
        'phone',
        'profile_photo_path',
        'university_id',
        'college_id',
        'major_id',
        'level',
        'gender',
        'country',
        'status',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'level'     => 'integer',
    ];

    // علاقات
    public function university()
    {
        return $this->belongsTo(University::class);
    }
    public function college()
    {
        return $this->belongsTo(College::class);
    }
    public function major()
    {
        return $this->belongsTo(Major::class);
    }

    // رابط صورة البروفايل
    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo_path ? asset('storage/' . $this->profile_photo_path) : null;
    }
}
