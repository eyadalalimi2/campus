<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Major extends Model
{
    use HasFactory;

    protected $fillable = [
        'college_id',
        'name',
        'is_active',
        'public_major_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*=============================
     | علاقات
     |=============================*/
    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    /** طلاب هذا التخصص */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /** البرامج المرتبطة عبر pivot major_program */
    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'major_program', 'major_id', 'program_id');
    }

    /** الأصول العامة المقيدة بهذا التخصص عبر pivot asset_audiences */
    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'asset_audiences', 'major_id', 'asset_id');
    }

    /** (اختياري) التخصص العام المرتبط، إذا كان العمود موجودًا */
    public function publicMajor(): BelongsTo
    {
        return $this->belongsTo(PublicMajor::class, 'public_major_id');
    }

    /*=============================
     | Accessors (مساعدة)
     |=============================*/
    /** إرجاع branch_id الخاص بالتخصص عبر الكلية */
    public function getBranchIdAttribute(): ?int
    {
        if ($this->relationLoaded('college') && $this->college) {
            return $this->college->branch_id;
        }
        return College::whereKey($this->college_id)->value('branch_id');
    }

    /** إرجاع university_id الخاص بالتخصص عبر الفرع ← الجامعة */
    public function getUniversityIdAttribute(): ?int
    {
        if ($this->relationLoaded('college') && $this->college && $this->college->relationLoaded('branch') && $this->college->branch) {
            return $this->college->branch->university_id;
        }
        // استعلام خفيف عبر join غير مباشر
        return UniversityBranch::whereKey($this->branch_id)->value('university_id');
    }

    /*=============================
     | Scopes (فلترة)
     |=============================*/
    public function scopeActive($q, $active = true)
    {
        return $q->where('is_active', (bool) $active);
    }

    public function scopeForCollege($q, ?int $collegeId)
    {
        return $collegeId ? $q->where('college_id', $collegeId) : $q;
    }

    /** فلترة بحسب فرع الجامعة عبر علاقة الكلية ← الفرع */
    public function scopeForBranch($q, ?int $branchId)
    {
        return $branchId
            ? $q->whereHas('college', fn($c) => $c->where('branch_id', $branchId))
            : $q;
    }

    /** فلترة بحسب الجامعة عبر علاقة الكلية ← الفرع ← الجامعة */
    public function scopeForUniversity($q, ?int $universityId)
    {
        return $universityId
            ? $q->whereHas('college', fn($c) => $c->whereHas('branch', fn($b) => $b->where('university_id', $universityId)))
            : $q;
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        return $q->where('name', 'like', '%' . $term . '%');
    }

    /** تصفية بحسب وجود برنامج محدد */
    public function scopeHasProgram($q, ?int $programId)
    {
        if (!$programId) return $q;
       return $q->whereHas('programs', fn($p) => $p->whereKey($programId));
    }

    /**
     * فلترة موحّدة لاستخدامها في الكنترولرز:
     * يدعم: q, university_id, branch_id, college_id, is_active, program_id
     */
    public function scopeFilter($q, array $f = [])
    {
        return $q
            ->when(isset($f['q']) && $f['q'] !== '',   fn($qq) => $qq->search($f['q']))
            ->when(!empty($f['university_id']),        fn($qq) => $qq->forUniversity((int) $f['university_id']))
            ->when(!empty($f['branch_id']),            fn($qq) => $qq->forBranch((int) $f['branch_id']))
            ->when(!empty($f['college_id']),           fn($qq) => $qq->forCollege((int) $f['college_id']))
            ->when(isset($f['is_active']),             fn($qq) => $qq->active($f['is_active']))
            ->when(!empty($f['program_id']),           fn($qq) => $qq->hasProgram((int) $f['program_id']));
    }

    /** ترتيب افتراضي: النشط أولاً ثم أبجديًا */
    public function scopeOrderDefault($q)
    {
        return $q->orderByDesc('is_active')->orderBy('name');
    }
}
