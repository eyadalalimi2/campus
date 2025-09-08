<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivationCode extends Model
{
    protected $table = 'activation_codes';

    protected $fillable = [
        'batch_id',
        'code',
        'plan_id',
        'university_id',
        'college_id',
        'major_id',
        'duration_days',
        'start_policy',
        'starts_on',
        'valid_from',
        'valid_until',
        'max_redemptions',
        'redemptions_count',
        'status',
        'redeemed_by_user_id',
        'redeemed_at',
        'created_by_admin_id',
    ];

    protected $casts = [
        'batch_id'            => 'integer',
        'plan_id'             => 'integer',
        'university_id'       => 'integer',
        'college_id'          => 'integer',
        'major_id'            => 'integer',
        'duration_days'       => 'integer',
        'starts_on'           => 'date',
        'valid_from'          => 'datetime',
        'valid_until'         => 'datetime',
        'max_redemptions'     => 'integer',
        'redemptions_count'   => 'integer',
        'redeemed_by_user_id' => 'integer',
        'redeemed_at'         => 'datetime',
        'created_by_admin_id' => 'integer',
    ];

    // علاقات
    public function batch(): BelongsTo { return $this->belongsTo(ActivationCodeBatch::class, 'batch_id'); }
    public function plan(): BelongsTo { return $this->belongsTo(Plan::class, 'plan_id'); }
    public function university(): BelongsTo { return $this->belongsTo(University::class, 'university_id'); }
    public function college(): BelongsTo { return $this->belongsTo(College::class, 'college_id'); }
    public function major(): BelongsTo { return $this->belongsTo(Major::class, 'major_id'); }
    public function redeemedBy(): BelongsTo { return $this->belongsTo(User::class, 'redeemed_by_user_id'); }
    public function createdBy(): BelongsTo { return $this->belongsTo(Admin::class, 'created_by_admin_id'); }

    // هل الكود قابل للتفعيل الآن؟
    public function getIsRedeemableAttribute(): bool
    {
        if ($this->status !== 'active') return false;
        $now = now();
        if ($this->valid_from && $now->lt($this->valid_from)) return false;
        if ($this->valid_until && $now->gt($this->valid_until)) return false;
        if ($this->redemptions_count >= $this->max_redemptions) return false;
        if ($this->start_policy === 'fixed_start' && empty($this->starts_on)) return false;
        return true;
    }
}
