<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivationCodeBatch extends Model
{
    use HasFactory;

    protected $table = 'activation_code_batches';

    protected $fillable = [
        'notes',
        'name',
        'plan_id',
        'university_id',
        'college_id',
        'major_id',
        'quantity',
        'status',
        'duration_days',
        'start_policy',
        'starts_on',
        'valid_from',
        'valid_until',
        'code_prefix',
        'code_length',
        'created_by_admin_id',
    ];

    protected $casts = [
        'starts_on'     => 'date',
        'valid_from'    => 'datetime',
        'valid_until'   => 'datetime',
        'quantity'      => 'integer',
        'duration_days' => 'integer',
        'code_length'   => 'integer',
    ];

    // علاقات
    public function activationCodes()
    {
        return $this->hasMany(ActivationCode::class, 'batch_id');
    }

    public function plan()
    {
        return $this->belongsTo(\App\Models\Plan::class, 'plan_id');
    }

    public function university()
    {
        return $this->belongsTo(\App\Models\University::class, 'university_id');
    }

    public function college()
    {
        return $this->belongsTo(\App\Models\College::class, 'college_id');
    }

    public function major()
    {
        return $this->belongsTo(\App\Models\Major::class, 'major_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by_admin_id');
    }

    // ملائمات
    public function isDraft(): bool { return $this->status === 'draft'; }
    public function isActive(): bool { return $this->status === 'active'; }
    public function isDisabled(): bool { return $this->status === 'disabled'; }
    public function isArchived(): bool { return $this->status === 'archived'; }
}
