<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'plan_id'             => 'integer',
        'university_id'       => 'integer',
        'college_id'          => 'integer',
        'major_id'            => 'integer',
        'quantity'            => 'integer',
        'duration_days'       => 'integer',
        'starts_on'           => 'date',
        'valid_from'          => 'datetime',
        'valid_until'         => 'datetime',
        'code_length'         => 'integer',
        'created_by_admin_id' => 'integer',
    ];

    public function plan(): BelongsTo { return $this->belongsTo(Plan::class, 'plan_id'); }
    public function createdBy(): BelongsTo { return $this->belongsTo(Admin::class, 'created_by_admin_id'); }
    public function activationCodes(): HasMany { return $this->hasMany(ActivationCode::class, 'batch_id'); }

    public function getDisplayNameAttribute(): string
    {
        return $this->name ?: ('دفعة #' . $this->id);
    }
}
