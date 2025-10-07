<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\DatabaseManager;

class College extends Model
{
    use HasFactory;

    protected $fillable = [
        'university_id',
        'branch_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*=============================
     | علاقات
     |=============================*/
    public function branch(): BelongsTo
    {
        return $this->belongsTo(UniversityBranch::class, 'branch_id');
    }

    public function majors(): HasMany
    {
        return $this->hasMany(Major::class);
    }

    /**
     * ملاحظة مهمة:
     * لا نُعرّف علاقة university() مباشرة لأن العلاقة هنا (College -> Branch -> University)
     * ليست مدعومة بعلاقة Eloquent أصلية بدون حزمة خارجية (belongsToThrough).
     * بدل ذلك نوفر Accessors عمليّة للحصول على الجامعة/معرّفها.
     */

    /** يرجّع كائن الجامعة المرتبط عبر الفرع (للاستخدام السريع في العرض) */
    public function getUniversityAttribute(): ?University
    {
        // إن كانت علاقة الفرع محمّلة مسبقًا سنستخدمها، وإلا نستعلم بشكل خفيف
        if ($this->relationLoaded('branch') && $this->branch) {
            return $this->branch->relationLoaded('university')
                ? $this->branch->university
                : University::find($this->branch->university_id);
        }

        $universityId = UniversityBranch::whereKey($this->branch_id)->value('university_id');
        return $universityId ? University::find($universityId) : null;
    }

    /** يرجّع معرّف الجامعة مباشرةً (يفيد في الفلترة السريعة) */
    public function getUniversityIdAttribute(): ?int
    {
        if ($this->relationLoaded('branch') && $this->branch) {
            return $this->branch->university_id;
        }

        return UniversityBranch::whereKey($this->branch_id)->value('university_id');
    }

    /*=============================
     | Scopes (فلترة شائعة)
     |=============================*/
    public function scopeActive($q, bool $active = true)
    {
        return $q->where('is_active', $active);
    }

    public function scopeForBranch($q, ?int $branchId)
    {
        return $branchId ? $q->where('branch_id', $branchId) : $q;
    }

    public function scopeForUniversity($q, ?int $universityId)
    {
        // فلترة الكليات حسب الجامعة عبر علاقة الفرع
        return $universityId
            ? $q->whereHas('branch', fn($b) => $b->where('university_id', $universityId))
            : $q;
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;

        return $q->where('name', 'like', "%{$term}%");
    }
}
