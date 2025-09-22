<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\MaterialTerm;

class AcademicTerm extends Model
{
    use HasFactory;

    /** أسماء الفصول (من enum: first, second, summer) */
    public const NAME_FIRST  = 'first';
    public const NAME_SECOND = 'second';
    public const NAME_SUMMER = 'summer';

    protected $fillable = [
        'calendar_id',
        'name',
        'starts_on',
        'ends_on',
        'is_active',
    ];

    protected $casts = [
        'starts_on' => 'date',
        'ends_on'   => 'date',
        'is_active' => 'boolean',
    ];

    /* ============================
     | علاقات
     |============================*/
    public function calendar(): BelongsTo
    {
        return $this->belongsTo(AcademicCalendar::class, 'calendar_id');
    }

    public function materials(): BelongsToMany
    {
        return $this->belongsToMany(Material::class, 'material_term', 'term_id', 'material_id')
            ->using(MaterialTerm::class)
            ->withPivot('id');
    }


    /* ============================
     | Scopes (فلترة)
     |============================*/
    public function scopeActive($q, $active = true)
    {
        return $q->where('is_active', (bool) $active);
    }

    public function scopeForCalendar($q, ?int $calendarId)
    {
        return $calendarId ? $q->where('calendar_id', $calendarId) : $q;
    }

    public function scopeBetween($q, ?string $from, ?string $to)
    {
        if ($from) $q->where('starts_on', '>=', $from);
        if ($to)   $q->where('ends_on',   '<=', $to);
        return $q;
    }

    /** الفاصل الزمني الحالي (اليوم بين البداية والنهاية) */
    public function scopeCurrent($q)
    {
        $today = now()->toDateString();
        return $q->where('starts_on', '<=', $today)->where('ends_on', '>=', $today);
    }
}
