<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\MaterialTerm;

class Material extends Model
{
    use HasFactory;

    /** نطاق المادة */
    public const SCOPE_GLOBAL     = 'global';
    public const SCOPE_UNIVERSITY = 'university';

    protected $fillable = [
        'name',
        'scope',         // global | university
        'university_id',
        'branch_id',
        'college_id',
        'major_id',
        'level',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level'     => 'integer',
    ];

    protected $appends = ['scope_label'];

    /* ============================
     | علاقات
     |============================*/
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }
    public function branch()
    {
        return $this->belongsTo(UniversityBranch::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class);
    }

    /** الأصول العامة المرتبطة بالمادة */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /**
     * الفصول الأكاديمية المرتبطة بالمادة عبر جدول material_term.
     * ملاحظة: الجدول يحتوي أعمدة (material_id, term_id, created_at) بدون updated_at.
     */
    public function terms(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(AcademicTerm::class, 'material_term', 'material_id', 'term_id')
            ->using(MaterialTerm::class)   // استخدام Pivot المخصص
            ->withPivot('id');             // للوصول إلى id في الـ pivot (اختياري)
    }


    /* ============================
     | Accessors
     |============================*/
    public function getScopeLabelAttribute(): string
    {
        return $this->scope === self::SCOPE_GLOBAL ? 'عالمي' : 'جامعة';
    }

    public function isGlobal(): bool
    {
        return $this->scope === self::SCOPE_GLOBAL;
    }

    public function isUniversityScoped(): bool
    {
        return $this->scope === self::SCOPE_UNIVERSITY;
    }

    /* ============================
     | Scopes (فلترة جاهزة)
     |============================*/
    public function scopeActive($q, $active = true)
    {
        return $q->where('is_active', (bool) $active);
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        return $q->where('name', 'like', '%' . $term . '%');
    }

    public function scopeScopeType($q, ?string $scope)
    {
        return $scope ? $q->where('scope', $scope) : $q;
    }

    public function scopeForUniversity($q, ?int $universityId)
    {
        return $universityId ? $q->where('university_id', $universityId) : $q;
    }

    public function scopeForCollege($q, ?int $collegeId)
    {
        return $collegeId ? $q->where('college_id', $collegeId) : $q;
    }

    public function scopeForMajor($q, ?int $majorId)
    {
        return $majorId ? $q->where('major_id', $majorId) : $q;
    }

    /** مواد مرتبطة بأي من term_ids (مصفوفة) عبر Pivot */
    public function scopeHasAnyTerm($q, ?array $termIds)
    {
        if (!$termIds || !count($termIds)) return $q;
        return $q->whereHas('terms', fn($t) => $t->whereIn('academic_terms.id', $termIds));
    }

    /**
     * مطابقة جمهور الطالب:
     * - إن كانت Global: تظهر للجميع.
     * - إن كانت University: يجب مطابقة الجامعة، ثم (الكلية NULL أو كلية الطالب)، ثم (التخصص NULL أو تخصص الطالب).
     */
    public function scopeMatchAudience($q, ?int $universityId, ?int $branchId = null, ?int $collegeId = null, ?int $majorId = null)
    {
        return $q->where(function ($w) use ($universityId, $branchId, $collegeId, $majorId) {
            // محتوى عام (global)
            $w->where('scope', self::SCOPE_GLOBAL);

            // محتوى على مستوى الجامعة
            if ($universityId) {
                $w->orWhere(function ($wu) use ($universityId, $branchId, $collegeId, $majorId) {
                    $wu->where('scope', self::SCOPE_UNIVERSITY)
                        ->where('university_id', $universityId)

                        // فلترة الفرع: يسمح بقيمة NULL أو فرع الطالب
                        ->where(function ($wb) use ($branchId) {
                            $wb->whereNull('branch_id');
                            if ($branchId) {
                                $wb->orWhere('branch_id', $branchId);
                            }
                        })

                        // فلترة الكلية
                        ->where(function ($wc) use ($collegeId) {
                            $wc->whereNull('college_id');
                            if ($collegeId) {
                                $wc->orWhere('college_id', $collegeId);
                            }
                        })

                        // فلترة التخصص
                        ->where(function ($wm) use ($majorId) {
                            $wm->whereNull('major_id');
                            if ($majorId) {
                                $wm->orWhere('major_id', $majorId);
                            }
                        });
                });
            }
        });
    }


    /**
     * فلترة موحّدة للاستخدام في الكنترولرز:
     * يدعم: q, scope, university_id, college_id, major_id, level, is_active, term_ids[]
     */
    public function scopeFilter($q, array $f = [])
    {
        return $q
            ->when(isset($f['q']) && $f['q'] !== '', fn($qq) => $qq->search($f['q']))
            ->when(!empty($f['scope']),            fn($qq) => $qq->scopeType($f['scope']))
            ->when(!empty($f['university_id']),    fn($qq) => $qq->forUniversity((int)$f['university_id']))
            ->when(!empty($f['college_id']),       fn($qq) => $qq->forCollege((int)$f['college_id']))
            ->when(!empty($f['major_id']),         fn($qq) => $qq->forMajor((int)$f['major_id']))
            ->when(isset($f['level']) && $f['level'] !== '', fn($qq) => $qq->where('level', (int)$f['level']))
            ->when(isset($f['is_active']),         fn($qq) => $qq->active($f['is_active']))
            ->when(
                !empty($f['term_ids']) && is_array($f['term_ids']),
                fn($qq) => $qq->hasAnyTerm($f['term_ids'])
            );
    }

    /** ترتيب افتراضي: النشط أولاً ثم أبجديًا ثم المستوى */
    public function scopeOrderDefault($q)
    {
        return $q->orderByDesc('is_active')->orderBy('name')->orderBy('level');
    }
}
