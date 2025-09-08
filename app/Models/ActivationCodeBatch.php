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

    // وسم الحالة بالعربي للعرض
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft'    => 'مسودة',
            'active'   => 'مفعّلة',
            'disabled' => 'موقوفة',
            'archived' => 'مؤرشفة',
            default    => $this->status,
        };
    }
}
