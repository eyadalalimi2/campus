<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /* ============================
     | علاقات
     |============================*/
    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    /** التخصصات المرتبطة عبر pivot major_program */
    public function majors(): BelongsToMany
    {
        return $this->belongsToMany(Major::class, 'major_program', 'program_id', 'major_id');
    }

    /* ============================
     | Scopes (فلترة)
     |============================*/
    public function scopeActive($q, $active = true)
    {
        return $q->where('is_active', (bool) $active);
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

    /** وجود تخصص معيّن مرتبط بالبرنامج */
    public function scopeHasMajor($q, ?int $majorId)
    {
        if (!$majorId) return $q;
        return $q->whereHas('majors', fn($m) => $m->where('major_id', $majorId));
    }

    /**
     * فلترة موحّدة لاستخدامها في الكنترولرز:
     * يدعم: q, discipline_id, is_active, major_id
     */
    public function scopeFilter($q, array $f = [])
    {
        return $q
            ->when(isset($f['q']) && $f['q'] !== '', fn($qq) => $qq->search($f['q']))
            ->when(!empty($f['discipline_id']),     fn($qq) => $qq->forDiscipline((int)$f['discipline_id']))
            ->when(isset($f['is_active']),          fn($qq) => $qq->active($f['is_active']))
            ->when(!empty($f['major_id']),          fn($qq) => $qq->hasMajor((int)$f['major_id']));
    }

    /** ترتيب افتراضي: النشط أولاً ثم أبجديًا */
    public function scopeOrderDefault($q)
    {
        return $q->orderByDesc('is_active')->orderBy('name');
    }
}
