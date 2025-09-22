<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'discipline_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*=============================
     | علاقات
     |=============================*/
    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /** التخصصات المرتبطة عبر pivot major_program (major_id, program_id) */
    public function majors(): BelongsToMany
    {
        return $this->belongsToMany(Major::class, 'major_program', 'program_id', 'major_id');
        // ->withTimestamps(); // لو عندك created_at/updated_at في pivot
        // ->withPivot([...]);  // لو عندك أعمدة إضافية على pivot
    }

    /*=============================
     | Scopes (فلترة)
     |=============================*/
    public function scopeActive($q, bool $active = true)
    {
        return $q->where('is_active', $active);
    }

    public function scopeForDiscipline($q, ?int $disciplineId)
    {
        return $disciplineId ? $q->where('discipline_id', $disciplineId) : $q;
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;
        return $q->where('name', 'like', '%'.$term.'%');
    }

    /** وجود تخصص معيّن مرتبط بالبرنامج (تصحيح مهم) */
    public function scopeHasMajor($q, ?int $majorId)
    {
        if (!$majorId) return $q;
        return $q->whereHas('majors', fn($m) => $m->whereKey($majorId));
        // أو: ->whereHas('majors', fn($m) => $m->where('majors.id', $majorId));
    }

    /**
     * فلاتر اختيارية مفيدة بحسب الهرم:
     * جامعة ← فرع ← كلية (تمر عبر majors -> college -> branch -> university)
     */
    public function scopeForUniversity($q, ?int $universityId)
    {
        if (!$universityId) return $q;
        return $q->whereHas('majors', function ($mj) use ($universityId) {
            $mj->whereHas('college', function ($cl) use ($universityId) {
                $cl->whereHas('branch', fn($br) => $br->where('university_id', $universityId));
            });
        });
    }

    public function scopeForBranch($q, ?int $branchId)
    {
        if (!$branchId) return $q;
        return $q->whereHas('majors', function ($mj) use ($branchId) {
            $mj->whereHas('college', fn($cl) => $cl->where('branch_id', $branchId));
        });
    }

    public function scopeForCollege($q, ?int $collegeId)
    {
        if (!$collegeId) return $q;
        return $q->whereHas('majors', fn($mj) => $mj->where('college_id', $collegeId));
    }

    /**
     * فلترة موحّدة لاستخدامها في الكنترولرز:
     * يدعم: q, discipline_id, is_active, major_id, university_id, branch_id, college_id
     */
    public function scopeFilter($q, array $f = [])
    {
        return $q
            ->when(isset($f['q']) && $f['q'] !== '', fn($qq) => $qq->search($f['q']))
            ->when(!empty($f['discipline_id']),     fn($qq) => $qq->forDiscipline((int)$f['discipline_id']))
            ->when(isset($f['is_active']),          fn($qq) => $qq->active((bool)$f['is_active']))
            ->when(!empty($f['major_id']),          fn($qq) => $qq->hasMajor((int)$f['major_id']))
            ->when(!empty($f['university_id']),     fn($qq) => $qq->forUniversity((int)$f['university_id']))
            ->when(!empty($f['branch_id']),         fn($qq) => $qq->forBranch((int)$f['branch_id']))
            ->when(!empty($f['college_id']),        fn($qq) => $qq->forCollege((int)$f['college_id']));
    }

    /** ترتيب افتراضي: النشط أولاً ثم أبجديًا */
    public function scopeOrderDefault($q)
    {
        return $q->orderByDesc('is_active')->orderBy('name');
    }
}
