<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'activation_code_id',
        'status',         // active | expired | cancelled | pending
        'started_at',
        'ends_at',
        'auto_renew',     // دائماً 0 في نظام الأكواد
        'price_cents',    // يظل NULL
        'currency',       // يظل NULL/ 'YER'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ends_at'    => 'datetime',
        'auto_renew' => 'boolean',
    ];

    // علاقات
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
    public function activationCode(): BelongsTo
    {
        return $this->belongsTo(ActivationCode::class, 'activation_code_id');
    }

    // سكوبات مفيدة
    public function scopeStatus($q, $status)
    {
        if ($status) $q->where('status', $status);
    }

    public function scopePlan($q, $planId)
    {
        if ($planId) $q->where('plan_id', (int)$planId);
    }
    public function scopeActive($q)
    {
        return $q->where('status', 'active')->where(function ($w) {
            $w->whereNull('ends_at')->orWhere('ends_at', '>', now());
        });
    }


    public function scopeDateBetween($q, $from, $to)
    {
        if ($from) $q->whereDate('started_at', '>=', $from);
        if ($to)   $q->whereDate('started_at', '<=', $to);
    }

    // لابل جاهز للحالة
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active'    => 'نشط',
            'expired'   => 'منتهي',
            'cancelled' => 'ملغى',
            'pending'   => 'قيد التفعيل',
            default     => $this->status ?: 'غير محدد',
        };
    }

    // اشتراك فعّال الآن؟
    public function getIsCurrentlyActiveAttribute(): bool
    {
        return $this->status === 'active'
            && $this->started_at
            && $this->ends_at
            && now()->between($this->started_at, $this->ends_at);
    }
}
