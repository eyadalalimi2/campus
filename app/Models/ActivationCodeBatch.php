<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActivationCodeBatch extends Model
{
    protected $table = 'activation_code_batches';

    protected $fillable = [
        'name',
        'notes',
        'plan_id',
        'university_id',
        'branch_id',
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
        'quantity'       => 'integer',
        'duration_days'  => 'integer',
        'starts_on'      => 'date',
        'valid_from'     => 'datetime',
        'valid_until'    => 'datetime',
    ];

    public function activationCodes(): HasMany
    {
        return $this->hasMany(ActivationCode::class, 'batch_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function university()
    {
        return $this->belongsTo(University::class, 'university_id');
    }
    public function branch()
    {
        return $this->belongsTo(\App\Models\UniversityBranch::class, 'branch_id');
    }

    public function college()
    {
        return $this->belongsTo(College::class, 'college_id');
    }
    
    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    // وسم الحالة بالعربي للعرض
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'    => 'مسودة',
            'active'   => 'نشطة',
            'disabled' => 'مُعطّلة',
            'archived' => 'مؤرشفة',
            default    => $this->status,
        };
    }
}
