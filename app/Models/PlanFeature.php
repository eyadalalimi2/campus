<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanFeature extends Model
{
    use HasFactory;

    protected $table = 'plan_features';

    protected $fillable = [
        'plan_id',
        'feature_key',
        'feature_value',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
    /** سكوب سريع لتصفية الميزات حسب الخطة */
    public function scopeForPlan($query, int $planId)
    {
        return $query->where('plan_id', $planId);
    }

    /** سكوب لتصفية حسب المفتاح */
    public function scopeKey($query, string $key)
    {
        return $query->where('feature_key', $key);
    }

    /** مساعدات اختيارية لتحويل القيمة لأنواع مفيدة */
    public function asBool(): bool
    {
        return filter_var($this->feature_value, FILTER_VALIDATE_BOOLEAN);
    }

    public function asInt(): ?int
    {
        return is_numeric($this->feature_value) ? (int) $this->feature_value : null;
    }
}
