<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivationCode extends Model
{
    use HasFactory;

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
        'starts_on'        => 'date',
        'valid_from'       => 'datetime',
        'valid_until'      => 'datetime',
        'redeemed_at'      => 'datetime',
        'duration_days'    => 'integer',
        'max_redemptions'  => 'integer',
        'redemptions_count'=> 'integer',
    ];

    // العلاقات
    public function batch()
    {
        return $this->belongsTo(ActivationCodeBatch::class, 'batch_id');
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

    public function redeemedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'redeemed_by_user_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by_admin_id');
    }

    // سكوبات مفيدة
    public function scopeActive($q)
    {
        return $q->where('status', 'active');
    }

    public function scopeByUniversity($q, $universityId)
    {
        return $q->where('university_id', $universityId);
    }

    public function scopeValidNow($q)
    {
        $now = now();
        return $q->where(function ($qq) use ($now) {
            $qq->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
        })->where(function ($qq) use ($now) {
            $qq->whereNull('valid_until')->orWhere('valid_until', '>=', $now);
        });
    }

    // دوال مساعدة
    public function isExpired(): bool
    {
        return $this->status === 'expired'
            || ($this->valid_until && $this->valid_until->isPast());
    }

    public function canRedeem(): bool
    {
        if ($this->status !== 'active') return false;
        if ($this->isExpired()) return false;
        return $this->redemptions_count < $this->max_redemptions;
    }
}
